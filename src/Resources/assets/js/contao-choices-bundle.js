import Choices from 'choices.js';

class ChoicesBundle
{
    static init()
    {
        if (!ChoicesBundle.hasOwnProperty('choiceInstances'))
        {
            ChoicesBundle.choiceInstances = [];
        }
        let elements = document.querySelectorAll('[data-choices="1"]');
        if (elements.length < 1)
        {
            return;
        }
        elements.forEach((element) => {
            let state = element.getAttribute('data-choice');
            if('active' !== state) {
                let defaultOptions = {};
                let options = {};
                if (element.hasAttribute('data-choices-options')) {
                    options = JSON.parse(element.getAttribute('data-choices-options'));
                    if (options.hasOwnProperty('addItemTextString'))
                    {
                        options.addItemText = (value) => {
                            return options.addItemTextString;
                        };
                        delete options.addItemTextString;
                    }
                    if (options.hasOwnProperty('maxItemTextString'))
                    {
                        options.maxItemText = (maxItemCount) => {
                            return options.maxItemTextString;
                        };
                        delete options.maxItemTextString;
                    }
                    options = Object.assign(defaultOptions, options);
                }

                let optionsEvent = new CustomEvent('hundhChoicesOptions', {
                    bubbles: true,
                    detail: {
                        options: options,
                    },
                });
                element.dispatchEvent(optionsEvent);

                let choice = new Choices(element, optionsEvent.detail.options);

                let event = new CustomEvent('hundhChoicesNewInstance', {
                    bubbles: true,
                    detail: {
                        instance: choice,
                    },
                });
                element.dispatchEvent(event);

                ChoicesBundle.choiceInstances.push({element: element, instance: choice});
            }
        });
    }

    static getChoiceInstances()
    {
        return ChoicesBundle.choiceInstances;
    }
}

document.addEventListener('DOMContentLoaded', ChoicesBundle.init);
document.addEventListener('filterAjaxComplete', ChoicesBundle.init);
document.addEventListener('formhybridToggleSubpaletteComplete', ChoicesBundle.init);
document.addEventListener('formhybrid_ajax_complete', ChoicesBundle.init);
document.addEventListener('formhybrid_ajax_start', (event) => {
    ChoicesBundle.choiceInstances.forEach((entry) => {
       if (event.target.contains(entry.element)) {
           entry.instance.disable();
       }
    });
});