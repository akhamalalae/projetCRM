// this variable is the list in the dom, it's initiliazed when the document is ready
var $collectionHolder;
// the link which we click on to add new items
var $addNewItem = $('<a href="#" class="btn btn-info"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Nouveau Champs</a>');
// when the page is loaded and ready
$(document).ready(function () {
    // get the collectionHolder, initilize the var by getting the list;
    $collectionHolder = $('#exp_list');
    // append the add new item link to the collectionHolder
    $collectionHolder.append($addNewItem);
    // add an index property to the collectionHolder which helps track the count of forms we have in the list
    $collectionHolder.data('index', $collectionHolder.find('.panel').length)
    // finds all the panels in the list and foreach one of them we add a remove button to it
    // add remove button to existing items
    $collectionHolder.find('.panel').each(function () {
        // $(this) means the current panel that we are at
        // which means we pass the panel to the addRemoveButton function
        // inside the function we create a footer and remove link and append them to the panel
        // more informations in the function inside
        addRemoveButton($(this));
    });

    // handle the click event for addNewItem
    $addNewItem.click(function (e) {
        // preventDefault() is your  homework if you don't know what it is
        // also look up preventPropagation both are usefull
        e.preventDefault();
        // create a new form and append it to the collectionHolder
        // and by form we mean a new panel which contains the form
        addNewForm();
    })
});
/*
* creates a new form and appends it to the collectionHolder
*/
function addNewForm() {
    // getting the prototype
    // the prototype is the form itself, plain html
    var prototype = $collectionHolder.data('prototype');
    // get the index
    // this is the index we set when the document was ready, look above for more info
    var index = $collectionHolder.data('index');
    var indexFormChamps = index;
    // create the form
    var newForm = prototype;
    // replace the __name__ string in the html using a regular expression with the index value
    newForm = newForm.replace(/__name__/g, index);
    // incrementing the index data and setting it again to the collectionHolder
    $collectionHolder.data('index', index+1);
    // create the panel
    // this is the panel that will be appending to the collectionHolder
    var $panel = $('<div class="panel panel-warning"><div class="panel-heading"></div></div>');
    // create the panel-body and append the form to it
    var $panelBody = $('<div class="panel-body"></div>').append(newForm);
    // append the body to the panel
    $panel.append($panelBody);
    // append the removebutton to the new panel
    addRemoveButton($panel);
    // append the panel to the addNewItem
    // we are doing it this way to that the link is always at the bottom of the collectionHolder
    $addNewItem.before($panel);

    $var = "#formulaire_champFormulaire_"+index+"_options";
    var select = document.querySelector($var);
    select.classList = 'new_item'+index;
    //style="display:none"
    var $addNewItemOptions = $('<a class="add_new_item_option btn btn-info" style="display:none" id="button_new_item'+index+'" data-collection-holder-class="new_item'+index+'"></i> Nouvelle option</a>');
    $collection = $($var);
    $collection.append($addNewItemOptions);

    $addNewItemOptions.click(function (e) {
        sessionStorage.setItem('idFormChamps', index);
        e.preventDefault();
        document.querySelectorAll('.add_new_item_option').forEach(btn => btn.addEventListener("click", addFormToCollection));
    });

    $("#formulaire_champFormulaire_"+index+"_referentiels").hide();
    $("select#formulaire_champFormulaire_"+index+"_type").change(function(){
        if($(this).val() == 5){
            $("#button_new_item"+index+"").show();
        }else{
            $("#button_new_item"+index+"").hide();
        }

        if($(this).val() == 6){
            $("#formulaire_champFormulaire_"+index+"_referentiels").show();
        }else{
            $("#formulaire_champFormulaire_"+index+"_referentiels").hide();
        }
    });
}

/**
* adds a remove button to the panel that is passed in the parameter
* @param $panel
*/
function addRemoveButton ($panel) {
    // create remove button
    //var $removeButton = $('<a href="#" class="btn btn-danger pull-right"><i class="fa fa-trash" aria-hidden="true"></i></a><br><br><hr><br>');
    var $removeButton = $('<a href="#" class="btn btn-danger pull-right"><i class="fa fa-trash" aria-hidden="true"></i> Supprimer le champ</a><br><br>');
    // appending the removebutton to the panel footer
    var $panelFooter = $('<div class="panel-footer"></div>').append($removeButton);
    // handle the click event of the remove button
    $removeButton.click(function (e) {
        e.preventDefault();
        // gets the parent of the button that we clicked on "the panel" and animates it
        // after the animation is done the element (the panel) is removed from the html
        $(e.target).parents('.panel').slideUp(1000, function () {
            $(this).remove();
        })
    });
    // append the footer to the panel
    $panel.append($panelFooter);
}

const addTagFormDeleteLink = (tagFormLi) => {
  const removeFormButton = document.createElement('button')
  removeFormButton.classList = 'btn btn-danger pull-right'
  removeFormButton.innerText = 'Supprimer l\'option'

  tagFormLi.append(removeFormButton);

  removeFormButton.addEventListener('click', (e) => {
      e.preventDefault()
      // remove the li for the tag form
      //tagFormLi.remove();
      $(e.target).parents('.li_option').slideUp(1000, function () {
          tagFormLi.remove();
      })
  });
}

const addFormToCollection = (e) => {
  var collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
  var index = $collectionHolder.data('index');

  var indexChampsFormulaire = sessionStorage.getItem('idFormChamps');
  const item = document.createElement('div');
  item.classList = 'li_option card border-info';

  item.innerHTML = collectionHolder
      .dataset
      .prototype
      .split('options_'+indexChampsFormulaire+'_').join("[options]["+index+"]")
      .split('[options]['+indexChampsFormulaire+']').join("[options]["+index+"]")
      .replace(
        /__name__/g,
        index
        )
  ;
  /*
  item.innerHTML = collectionHolder
      .dataset
      .prototype
      .replace(
      /options_0_/g,
      "options_"+index+"_"
      )
      .replace(
      /options_2_/g,
      "options_"+index+"_"
      )
  ;
  */

  collectionHolder.appendChild(item);
  addTagFormDeleteLink(item)
  $collectionHolder.data('index', index+1);
};



