import Choices from 'choices.js';

class ChoicesBundle
{
    static init()
    {
        if (!ChoicesBundle.hasOwnProperty('choiceInstances')) {
            ChoicesBundle.choiceInstances = [];
        }

        let elements = document.querySelectorAll('[data-choices="1"]');
        if (elements.length < 1) {
            return;
        }

        elements.forEach((element) => {
            const state = element.getAttribute('data-choice');
            if (state === 'active') {
                return;
            }

            let options = {};

            const jsonChoicesOptions = element.getAttribute('data-choices-options');
            if (jsonChoicesOptions !== null && jsonChoicesOptions !== '')
            {
                options = JSON.parse(jsonChoicesOptions);
                const { addItemTextString, maxItemTextString } = options;

                if (addItemTextString !== undefined) {
                    options.addItemText = _ => addItemTextString;
                    delete options.addItemTextString;
                }

                if (maxItemTextString !== undefined) {
                    options.maxItemText = _ => maxItemTextString;
                    delete options.maxItemTextString;
                }
            }

            let optionsEvent = new CustomEvent('hundhChoicesOptions', {
                bubbles: true,
                detail: {
                    options: options,
                },
            });
            element.dispatchEvent(optionsEvent);

            const choices = new Choices(element, optionsEvent.detail.options);
            const newInstanceEvent = new CustomEvent('hundhChoicesNewInstance', {
                bubbles: true,
                detail: {
                    instance: choices,
                },
            });
            element.dispatchEvent(newInstanceEvent);

            ChoicesBundle.choiceInstances.push({element: element, instance: choices});
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
