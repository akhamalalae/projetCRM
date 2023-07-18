//////point de ventes //////

// this variable is the list in the dom, it's initiliazed when the document is ready
var $collectionHolderpointVentes;
// the link which we click on to add new items
var $addNewItempointVentes = $('<a href="#" class="btn btn-info"><i class="fa fa-plus-square-o" aria-hidden="true"></i></a>');
// when the page is loaded and ready
$(document).ready(function () {
    // get the collectionHolderpointVentes, initilize the var by getting the list;
    $collectionHolderpointVentes = $('#pointVentes_list');
    // append the add new item link to the collectionHolderpointVentes
    $collectionHolderpointVentes.append($addNewItempointVentes);
    // add an index property to the collectionHolderpointVentes which helps track the count of forms we have in the list
    $collectionHolderpointVentes.data('index', $collectionHolderpointVentes.find('.panel').length)
    // finds all the panels in the list and foreach one of them we add a remove button to it
    // add remove button to existing items
    $collectionHolderpointVentes.find('.panel').each(function () {
        // $(this) means the current panel that we are at
        // which means we pass the panel to the addRemoveButton function
        // inside the function we create a footer and remove link and append them to the panel
        // more informations in the function inside
        addRemoveButtonpointVentes($(this));
    });

    // handle the click event for addNewItem
    $addNewItempointVentes.click(function (e) {
        // preventDefault() is your  homework if you don't know what it is
        // also look up preventPropagation both are usefull
        e.preventDefault();
        // create a new form and append it to the collectionHolderpointVentes
        // and by form we mean a new panel which contains the form
        addNewFormpointVentes();
    })
});
/*
* creates a new form and appends it to the collectionHolder
*/
function addNewFormpointVentes() {
    // getting the prototype
    // the prototype is the form itself, plain html
    var prototype = $collectionHolderpointVentes.data('prototype');
    // get the index
    // this is the index we set when the document was ready, look above for more info
    var index = $collectionHolderpointVentes.data('index');
    // create the form
    var newForm = prototype;
    // replace the __name__ string in the html using a regular expression with the index value
    newForm = newForm.replace(/__name__/g, index);
    //onchange="myFunctionType(0)"  'new_item'+index;
    functionName = 'onchange="myFunctionType('+index+')"';
    newForm = newForm.replace('onchange="myFunctionType(0)"', functionName);
    // incrementing the index data and setting it again to the collectionHolderpointVentes
    $collectionHolderpointVentes.data('index', index+1);
    // create the panel
    // this is the panel that will be appending to the collectionHolderpointVentes
    var $panel = $('<div class="panel panel-warning"><div class="panel-heading"></div></div>');
    // create the panel-body and append the form to it
    var $panelBody = $('<div class="panel-body"></div>').append(newForm);
    // append the body to the panel
    $panel.append($panelBody);
    // append the removebutton to the new panel
    addRemoveButtonpointVentes($panel);
    // append the panel to the addNewItempointVentes
    // we are doing it this way to that the link is always at the bottom of the collectionHolderpointVentes
    $addNewItempointVentes.before($panel);
}

/**
* adds a remove button to the panel that is passed in the parameter
* @param $panel
*/
function addRemoveButtonpointVentes($panel) {
    // create remove button
    var $removeButton = $('<a href="#" class="btn btn-danger pull-right"><i class="fa fa-trash" aria-hidden="true"></i></a><br><br>');
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





// this variable is the list in the dom, it's initiliazed when the document is ready
var $collectionHolder;
// the link which we click on to add new items
var $addNewItem = $('<a href="#" class="btn btn-info"><i class="fa fa-plus-square-o" aria-hidden="true"></i></a>');
// when the page is loaded and ready
$(document).ready(function () {
    // get the collectionHolder, initilize the var by getting the list;
    $collectionHolder = $('#produits_list');
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

    $var = "#entreprise_produits_"+index+"_categories";
    var select = document.querySelector($var);
    select.classList.add('js-multiple', 'multiple');
    $(function() {
        $('.js-multiple').select2({
            placeholder: 'Choisir'
        });
    });
}

/**
* adds a remove button to the panel that is passed in the parameter
* @param $panel
*/
function addRemoveButton ($panel) {
    // create remove button
    var $removeButton = $('<a href="#" class="btn btn-danger pull-right"><i class="fa fa-trash" aria-hidden="true"></i></a><br><br>');
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