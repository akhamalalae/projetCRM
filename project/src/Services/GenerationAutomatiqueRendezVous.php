<?php

namespace App\Services;

use App\Entity\Entreprise;
use App\Entity\Formulaire;
use App\Entity\HistoriqueGenerationAutomatiqueRouting;
use App\Entity\PointVente;
use App\Entity\User;
use App\Entity\RenderVous;
use Doctrine\ORM\EntityManagerInterface;
use App\RabbitMQ\Message\MailNotification;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;
use DateInterval;

class GenerationAutomatiqueRendezVous
{
    protected HistoriqueGenerationAutomatiqueRouting  $historique;
    protected ArrayCollection                         $formulaires;
    protected DateTime                                $dateDebut;
    protected DateTime                                $dateFin;
    protected int                                     $ecart;
    protected User                                    $user;

    const FORMAT_DATE               = 'Y-m-d H:i:s';

    public function __construct(private EntityManagerInterface $em, private MessageBusInterface $messageBus)
    {
    }

    /**
     * Initialisation
     *
     * @return void
     */
    public function init()
    {
        $this->user        = $this->historique->getUserCreateur();
        $this->formulaires = $this->historique->getFormulaires();
        $this->ecart       = $this->historique->getEcartEnMunites();
        $this->dateDebut   = $this->historique->getDateDebut();
        $this->dateFin     = $this->addEcartToDate($this->dateDebut, $this->ecart);
    }

    /**
     * create historique
     *
     * @param HistoriqueGenerationAutomatiqueRouting $historique
     *
     * @return void
     */
    public function create($historique)
    {
        $this->historique = $historique;
        $this->init();

        foreach ($this->formulaires as $formulaire) {
            $intervenents = $formulaire->getIntervenants();
            $entreprises  = $formulaire->getEntreprises();

            foreach ($entreprises as $entreprise) {
                $pointeVentes = $entreprise->getPointVentes();

                foreach ($pointeVentes as $pointeVente) {

                    foreach ($intervenents as $intervenent) {

                        $this->creerPlanningRendezVous($intervenent);

                        $this->createRendezVous($formulaire, $pointeVente, $intervenent, $entreprise);
                    }
                }
            }
        }
    }

     /**
     * Créer un planning de rendez-vous
     *
     * @param User $intervenent
     *
     * @return void
     */
    public function creerPlanningRendezVous(User $intervenent)
    {
        $nbrRdv = $this->countdRendezVousByIntervenentBetweenTowDate($intervenent);

        while($nbrRdv !== 0) {
            $this->dateDebut = $this->addEcartToDate($this->dateDebut, $this->ecart);

            $this->checkHolidayAndWeekend();
            $this->checkWorkTime();

            $this->dateFin   = $this->addEcartToDate($this->dateDebut, $this->ecart);

            $nbrRdv = $this->countdRendezVousByIntervenentBetweenTowDate($intervenent);
        }
    }

    /**
     * check Work Time
     *
     * @return void
     */
    public function checkWorkTime()
    {
        if (intval($this->dateDebut->format('H')) < 8 || intval($this->dateFin->format('H')) < 8) {
            dump("<8");
            $this->dateDebut = $this->createDateFormat(
                $this->dateDebut->format('Y-m-d') . " 08:00:00"
            );
        }

        if (intval($this->dateDebut->format('H')) > 18 || intval($this->dateFin->format('H')) > 18) {
            dump("> 18 ");
            $this->dateDebut = $this->createDateFormat(
                $this->addDaysToDate($this->dateDebut, 1)->format('Y-m-d') . " 08:00:00"
            );
        }

        if(
            (intval($this->dateDebut->format('H')) >= 12 && intval($this->dateDebut->format('H')) <= 14)
            || (intval($this->dateFin->format('H')) >= 12 && intval($this->dateFin->format('H')) <= 14)
        ) {
            dump(" >= 12 , <= 14");
            $this->dateDebut = $this->createDateFormat(
                $this->dateDebut->format('Y-m-d') . " 14:00:00"
            );
        }

        dump($this->dateDebut);
    }

    /**
     * check Holiday And Weekend
     *
     * @return void
     */
    public function checkHolidayAndWeekend()
    {
        $holiday = [
            '01 Jan' => 'New Year Day',
            '18 Jan' => 'Martin Luther King Day',
            '22 Feb' => 'Washington\'s Birthday',
            '05 Jul' => 'Independence Day',
            '11 Nov' => 'Veterans Day',
            '24 Dec' => 'Christmas Eve',
            '25 Dec' => 'Christmas Day',
            '31 Dec' => 'New Year Eve'
        ];

        if ($this->dateDebut->format('l') == 'Saturday') {
            $this->dateDebut = $this->createDateFormat(
                $this->addDaysToDate($this->dateDebut, 2)->format('Y-m-d') . " 08:00:00"
            );
        }

        if ($this->dateDebut->format('l') == 'Sunday' && array_key_exists($this->dateDebut->format('d M'), $holiday)) {
            $this->dateDebut = $this->createDateFormat(
                $this->addDaysToDate($this->dateDebut, 1)->format('Y-m-d') . " 08:00:00"
            );
        }
    }

    /**
     * createFromFormat
     *
     * @param string $date
     *
     * @return DateTime
     */
    public function createDateFormat(string $date)
    {
        return DateTime::createFromFormat(self::FORMAT_DATE, $date);
    }

    /**
     * create calander
     *
     * @param Formulaire $formulaire
     * @param PointVente $pointeVente
     * @param User       $intervenent
     * @param Entreprise $entreprise
     *
     * @return void
     */
    public function createRendezVous(
            Formulaire $formulaire,
            PointVente $pointeVente,
            User       $intervenent,
            Entreprise $entreprise
    ){
        $calendar = new RenderVous();

        $title = sprintf('[%s] %s', $intervenent->getLastname(), $pointeVente->getLibelle());

        $calendar
            ->setTitle($title)
            ->setStart($this->dateDebut)
            ->setEnd($this->dateFin)
            ->setDescription($pointeVente->getLibelle())
            ->setAllDay(false)
            ->setBackgroundColor($this->colorHex())
            ->setBorderColor($this->colorHex())
            ->setTextColor($this->colorHex())
            ->setTextColor("#000000")
            ->setFormulaire($formulaire)
            ->setUserCreateur($this->user)
            ->setEffectuer(false)
            ->setPointeVente($pointeVente)
            ->setIntervenant($intervenent)
            ->setEntreprise($entreprise)
            ->setHistoriqueGenerationAutomatiqueRouting($this->historique);

        $this->em->persist($calendar);
        $this->em->flush();

        $this->sendMail($calendar);
    }

    /**
     * Send Mail
     *
     * @param RenderVous $calendar
     *
     * @return void
     */
    public function sendMail(RenderVous $calendar)
    {
        $description = sprintf(
            '
                <h1> Rendez vous %s !</h1>
                <p> Date de Debut %s </p>
                <p> Date de Fin %s </p>
                <p> Formulaire %s </p>
                <p> Entreprise %s </p>
                <p> Point de vente  %s </p>
            ',
            $calendar->getTitle(),
            $calendar->getStart()->format(self::FORMAT_DATE),
            $calendar->getEnd()->format(self::FORMAT_DATE),
            $calendar->getFormulaire()->getLibelle(),
            $calendar->getEntreprise()->getFormeJuridique(),
            $calendar->getPointeVente()->getLibelle()
        );

        $this->messageBus->dispatch(new MailNotification(
            $description,
            $calendar->getId(),
            $calendar?->getIntervenant()?->getEmail()
        ));
    }

    /**
     * add ecart to date
     *
     * @param DateTime $date
     * @param int      $ecart
     *
     * @return DateTime
     */
    public function addEcartToDate(DateTime $date, int $ecart)
    {
        $cloneDate = clone $date;

        return $cloneDate->add(new DateInterval(sprintf('PT%sM', $ecart)));
    }

    /**
     * add days to date
     *
     * @param DateTime $date
     * @param int      $days
     *
     * @return DateTime
     */
    public function addDaysToDate(DateTime $date, int $days)
    {
        $cloneDate = clone $date;

        return $cloneDate->modify(sprintf('+%s day', $days));
    }

    /**
     * get count rendez vous
     *
     * @param User $intervenent
     *
     * @return void
     */
    public function countdRendezVousByIntervenentBetweenTowDate(User $intervenent)
    {
        return count($this->em->getRepository(RenderVous::class)->findRendezVousByIntervenentBetweenTowDate(
            $this->dateDebut,
            $this->dateFin,
            $intervenent)
        );
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