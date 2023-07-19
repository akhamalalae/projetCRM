<?php

namespace App\Controller\ModuleGestionOutilsPilotage;

use DateTime;
use DateInterval;
use App\Entity\RenderVous;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\HistoriqueGenerationAutomatiqueRouting;
use App\Form\Planning\GenerationAutomatiqueRendezVousType;

class GenerationAutomatiqueRendezVous extends BaseController
{
    /**
     * @Route("/gestionnaire/generation/automatique/rendez/vous", name="generation_automatique_rendez_vous", methods={"GET","POST"})
     */
    public function generationAutomatiqueRendezVous(Request $request): Response
    {
        ini_set('memory_limit','2048M');
        set_time_limit(600);
        $menus = $this->serviceMenu();
        $user = $this->getUser();
        $dateNoow = new DateTime();
        $defaultData = ['message' => 'defaultData'];

        $routings = $this->em->getRepository(HistoriqueGenerationAutomatiqueRouting::class)->findBy(['userCreateur' => $user]);

        $form = $this->createForm(GenerationAutomatiqueRendezVousType::class, $defaultData);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dateDebut = $form->getData()["start"];
            $ecart = $form->getData()["nbrMinutes"];
            $formulaires = $form->getData()["formulaires"];

            $historiqueGenerationAutomatiqueRouting = $this->historiqueGenerationAutomatiqueRouting($dateDebut, $ecart, $formulaires, $dateNoow);

            foreach ($formulaires as $formulaire) {
                $intervenents = $formulaire->getIntervenants();
                $entreprises = $formulaire->getEntreprises();

                foreach ($entreprises as $entreprise) {
                    $pointeVentes = $entreprise->getPointVentes();

                    foreach ($pointeVentes as $pointeVente) {

                        foreach ($intervenents as $intervenent) {
                            //$dateDebutBeforeModifications = clone $dateDebut;
                            $dateDebut = $this->getValidDateDebutRendezVous($intervenent, $dateDebut, $ecart);
                            $dateFin = $this->getDateFin($dateDebut, $ecart);

                            if(
                                1 === 1
                                //&& $this->checkDistance($intervenent, $pointeVente) < 10
                            )
                            {
                                $this->createRendezVous($dateDebut, $dateFin, $pointeVente, $formulaire, $intervenent, $entreprise, $historiqueGenerationAutomatiqueRouting);
                            }

                            //$dateDebut = $dateDebutBeforeModifications;
                        }
                    }
                }
            }

            //return $this->redirectToRoute('generation_automatique_rendez_vous');
        }

        return $this->render('calendrierRenderVous/generationAutomatiqueRendezVous.html.twig', [
            'menus' => $menus,
            'current_page' => 'automatisation_routing',
            'routings' => $routings,
            'form' => $form->createView(),
        ]);
    }

    public function historiqueGenerationAutomatiqueRouting($dateDebut, $ecart, $formulaires, $dateNow)
    {
        $historiqueGenerationAutomatiqueRouting = new HistoriqueGenerationAutomatiqueRouting();
        $historiqueGenerationAutomatiqueRouting->setDateDebut($dateDebut);
        $historiqueGenerationAutomatiqueRouting->setEcartEnMunites($ecart);
        $historiqueGenerationAutomatiqueRouting->setDateCreation($dateNow);
        $historiqueGenerationAutomatiqueRouting->setUserCreateur($this->getUser());
        foreach ($formulaires as $formulaire) {
            $historiqueGenerationAutomatiqueRouting->addFormulaire($formulaire);
        }
        $this->doctrinePersist($historiqueGenerationAutomatiqueRouting);
        $this->doctrineFlush();

        return $historiqueGenerationAutomatiqueRouting;
    }

    public function createRendezVous($dateDebut, $dateFin, $pointeVente, $formulaire, $intervenent, $entreprise, $historiqueGenerationAutomatiqueRouting)
    {
        $user = $this->getUser();
        $calendar = new RenderVous();
        $calendar->setTitle('[' . $intervenent->getLastname() . '] ' . $pointeVente->getLibelle());
        $calendar->setStart($dateDebut);
        $calendar->setEnd($dateFin);
        $calendar->setDescription($pointeVente->getLibelle());
        $calendar->setAllDay(false);
        $calendar->setBackgroundColor($this->colorHex());
        $calendar->setBorderColor($this->colorHex());
        $calendar->setTextColor($this->colorHex());
        $calendar->setTextColor("#000000");
        $calendar->setFormulaire($formulaire);
        $calendar->setUserCreateur($user);
        $calendar->setEffectuer(false);
        $calendar->setPointeVente($pointeVente);
        $calendar->setIntervenant($intervenent);
        $calendar->setEntreprise($entreprise);
        $calendar->setHistoriqueGenerationAutomatiqueRouting($historiqueGenerationAutomatiqueRouting);

        $this->doctrinePersist($calendar);
        $this->doctrineFlush();
    }

    public function getValidDateDebutRendezVous($intervenent, $dateDebut, $ecart)
    {
        $nbrRdv = 1;

        while($nbrRdv !== 0) {
            $dateDebut = $this->checkHolidayAndWeekend($dateDebut);
            $dateDebut = $this->checkWorkTime($dateDebut, $ecart);
            $dateDebut = $this->checkLunchBreakTime($dateDebut, $ecart);
            $dateFin = $this->getDateFin($dateDebut, $ecart);
            $nbrRdv = $this->countdRendezVousByIntervenentBetweenTowDate($dateDebut, $dateFin, $intervenent);
            $dateDebut = ($nbrRdv === 0) ? $dateDebut : $dateFin;
        }

        return $dateDebut;
    }

    public function countdRendezVousByIntervenentBetweenTowDate($dateDebut, $dateFin, $intervenent)
    {
        return count($this->em->getRepository(RenderVous::class)->findRendezVousByIntervenentBetweenTowDate($dateDebut, $dateFin, $intervenent));
    }

    public function checkWorkTime($dateDebut, $ecart)
    {
        $dateFin = $this->getDateFin($dateDebut, $ecart);

        if($dateDebut->format('H') < 8 || $dateFin->format('H') < 8) {
            $dateDebut = $dateDebut->format('Y-m-d') . " 08:00:00";
            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $dateDebut);
        }

        if($dateDebut->format('H') >= 18 || $dateFin->format('H') >= 18) {
            $dateDebut = $dateDebut->modify('+1 day');
            $dateDebut = $dateDebut->format('Y-m-d') . " 08:00:00";
            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $dateDebut);
        }

        return $dateDebut;
    }

    public function checkLunchBreakTime($dateDebut, $ecart)
    {
        $dateFin = $this->getDateFin($dateDebut, $ecart);
        if(
            ($dateDebut->format('H') >= 12 && $dateDebut->format('H') <= 14)
            ||
            ($dateFin->format('H') >= 12 && $dateFin->format('H') <= 14)
        ) {
            $dateDebut = $dateDebut->format('Y-m-d') . " 14:00:00";
            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $dateDebut);
        }

        return $dateDebut;
    }

    public function checkHolidayAndWeekend($date)
    {
        if($date->format('l') == 'Saturday') {
            $dateDebut = $date->modify('+2 day');
            $dateDebut = $dateDebut->format('Y-m-d') . " 08:00:00";
            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $dateDebut);
            return $dateDebut;
        }else if($date->format('l') == 'Sunday') {
            $dateDebut = $date->modify('+1 day');
            $dateDebut = $dateDebut->format('Y-m-d') . " 08:00:00";
            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $dateDebut);
            return $dateDebut;
        }else {
            //$receivedDate = date('d M', strtotime($date));
            $receivedDate = $date->format('d M');

            $holiday = array(
                '01 Jan' => 'New Year Day',
                '18 Jan' => 'Martin Luther King Day',
                '22 Feb' => 'Washington\'s Birthday',
                '05 Jul' => 'Independence Day',
                '11 Nov' => 'Veterans Day',
                '24 Dec' => 'Christmas Eve',
                '25 Dec' => 'Christmas Day',
                '31 Dec' => 'New Year Eve'
            );

            foreach($holiday as $key => $value){
                if($receivedDate == $key){
                    $dateDebut = $date->modify('+1 day');
                    $dateDebut = $dateDebut->format('Y-m-d') . " 08:00:00";
                    $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $dateDebut);
                    return $dateDebut;
                }
            }
        }

        return $date;
    }

    public function addMinutesToDate($dateDebut, $ecart)
    {
        return $dateDebut->add(new DateInterval('PT' . $ecart . 'M'));
    }

    public function getDateFin($dateDebut, $ecart)
    {
        return $this->addMinutesToDate($dateDebut, $ecart);
    }

    function checkDistance($intervenent, $pointeVente)
    {
        $adressePointeVente = $this->concatenateAdresse($pointeVente);
        $latLongPointeVente = $this->getLatLong($adressePointeVente);
        $adresseintervenent = $this->concatenateAdresse($intervenent);
        $latLongIntervenent = $this->getLatLong($adresseintervenent);
        $unit = 'kilometers';

        $distance = $this->getDistanceBetweenTwoPoints(
            $latLongPointeVente['lat'],
            $latLongPointeVente['long'],
            $latLongIntervenent['lat'],
            $latLongIntervenent['long'],
            $unit
        );

        return $distance;
    }

    public function concatenateAdresse($object)
    {
        $adresse = $object->getAdresse();
        $complementAdresse = ($object->getComplementAdresse() !== null) ? ' ' . $object->getComplementAdresse() : '';
        $cp = ($object->getVille() !== null) ? ' ,' . $object->getVille()->getCode() : '';

        return $adresse . $complementAdresse . $cp;
    }

    function getLatLong($address)
    {
        $array = array();
        $geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');

        // We convert the JSON to an array
        $geo = json_decode($geo, true);

        // If everything is cool
        if ($geo['status'] = 'OK') {
           $latitude = $geo['results'][0]['geometry']['location']['lat'];
           $longitude = $geo['results'][0]['geometry']['location']['lng'];
           $array = array('lat'=> $latitude ,'lng'=>$longitude);
        }

        return $array;
    }

    function getDistanceBetweenTwoPoints($latitude1, $longitude1, $latitude2, $longitude2, $unit)
    {
        $theta = $longitude1 - $longitude2;
        $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;

        switch($unit) {
            case 'miles':
                break;
            case 'kilometers' :
                $distance = $distance * 1.609344;
        }

        return (round($distance,2));
    }

    function getLastRendezVous($intervenant)
    {
        $all = $this->em->getRepository(RenderVous::class)->findBy(
            ['intervenant' => $intervenant,],
            ['start' => 'ASC']
        );

        return $all[count($all)-1];
    }

    public function colorHex()
    {
        $hex = '#';
        foreach(array('r', 'g', 'b') as $color){
            //Random number between 0 and 255.
            $val = mt_rand(0, 255);
            //Convert the random number into a Hex value.
            $dechex = dechex($val);
            //Pad with a 0 if length is less than 2.
            if(strlen($dechex) < 2){
                $dechex = "0" . $dechex;
            }
            //Concatenate
            $hex .= $dechex;
        }
        return  $hex;
    }
}