/**
 * App module
 */
 const app = {

    /**
     * Init method who contains the firsts run codes 
     */
    init: function() {

        console.log('coucou');
        ingredientForm.init();
    },

}

document.addEventListener('DOMContentLoaded', app.init);
