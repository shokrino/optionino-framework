document.addEventListener("DOMContentLoaded", function() {
    const tabContents = document.querySelectorAll('.tabcontent');
    const tabLinks = document.querySelectorAll('.tablinks');

    if (tabContents.length > 0 && tabLinks.length > 0) {
        tabContents[0].style.display = "block";
        tabLinks[0].classList.add("active");
    } else {
        console.error("Elements with class .tabcontent or .tablinks not found.");
    }

    const OPTNNO = document.getElementById('optionino');
    const form = document.getElementById('save-options-optionino');
    const errorText = form.querySelector('.error-text');
    const successText = form.querySelector('.success-text');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        OPTNNO.classList.add('loading');
        errorText.style.display = 'none';
        successText.style.display = 'none';

        let formData = new FormData(form);
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');

        checkboxes.forEach(checkbox => {
            const checkboxName = checkbox.name;
            if (!formData.has(checkboxName)) {
                formData.set(checkboxName, 'off');
            }
        });

        const repeaterFields = document.querySelectorAll('.optionino-repeater-field[data-repeater-name]');
        repeaterFields.forEach(repeaterField => {
            const repeaterName = repeaterField.getAttribute('data-repeater-name');
            const repeaterValues = collectRepeaterValues(repeaterField);
            formData.append(repeaterName, repeaterValues);
            console.log(repeaterValues);
        });

        formData.append('action', 'save_optionino_data');
        formData.append('security', data_optionino.nonce);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', data_optionino.ajax_url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function() {
            OPTNNO.classList.remove('loading');
            const response = JSON.parse(xhr.responseText);

            if (xhr.status === 200) {
                if (response.success) {
                    successText.textContent = response.data.message;
                    successText.style.display = 'block';
                } else {
                    errorText.textContent = response.data.message;
                    errorText.style.display = 'block';
                }
            } else {
                console.error('Request failed with status:', xhr.status);
                errorText.textContent = "An error occurred while processing your request.";
                errorText.style.display = 'block';
            }
        };

        xhr.onerror = function() {
            OPTNNO.classList.remove('loading');
            errorText.textContent = "An error occurred while processing your request.";
            errorText.style.display = 'block';
        };

        xhr.send(formData);
    });

    function collectRepeaterValues(repeaterField) {
        const repeaterItems = repeaterField.querySelectorAll('.optionino-repeater-item');
        const mainArray = [];

        repeaterItems.forEach((repeaterItem) => {
            const subArray = {};
            repeaterItem.querySelectorAll('input, textarea, select').forEach(input => {
                subArray[input.name.replace(/_\d+$/, '').replace(/^_/, '')] = input.value;
            });
            mainArray.push(encodeRepeaterValues(subArray));
        });

        return encodeRepeaterValues(mainArray);
    }

    function encodeRepeaterValues(values) {
        const params = new URLSearchParams();
        for (const key in values) {
            if (values.hasOwnProperty(key)) {
                if (Array.isArray(values[key])) {
                    params.append(key, JSON.stringify(values[key]));
                } else {
                    params.append(key, values[key]);
                }
            }
        }
        return params.toString();
    }

    const fields = Array.from(document.querySelectorAll('.optionino-box-option'));
    const fieldsWithRequire = Array.from(document.querySelectorAll('[data-require-0]'));

    function checkConditions(field, operator, requiredValue) {
        const requiredField = document.getElementById(field);
        if (!requiredField) return false;

        let value;
        if (requiredField.tagName === 'SELECT') {
            value = requiredField.value;
        } else if (requiredField.type === 'checkbox') {
            value = requiredField.checked;
        } else {
            value = requiredField.value;
        }

        switch (operator) {
            case '=':
                return value == requiredValue;
            case '!=':
                return value != requiredValue;
            case 'or':
                if (Array.isArray(requiredValue)) {
                    return requiredValue.some(subVal => value == subVal);
                }
                return false;
            default:
                return false;
        }
    }

    function updateFieldVisibility(field) {
        const requiredFieldConditions = Array.from(field.attributes)
            .filter(attr => attr.name.startsWith('data-require-'))
            .map(attr => JSON.parse(attr.value || 'null'))
            .filter(Boolean);
        field.setAttribute('display', "true");
        requiredFieldConditions.forEach(condition => {
            if (!checkConditions(condition[0], condition[1], condition[2])) {
                field.setAttribute('display', "false");
            }
        });
    }

    function updateConditionalOptionsDisplay() {
        const conditionalOptions = Array.from(document.querySelectorAll('.optionino-conditional-option'));
        conditionalOptions.forEach(option => {
            const attributeValue = option.getAttribute('display');
            option.style.display = attributeValue === 'true' ? 'flex' : 'none';
        });
    }

    function updateOnLoadAndChange() {
        fieldsWithRequire.forEach(updateFieldVisibility);
        updateConditionalOptionsDisplay();
    }

    updateOnLoadAndChange();
    fields.forEach(input => input.addEventListener('change', updateOnLoadAndChange));

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('optionino-add-repeater-item')) {
            event.preventDefault();
            const repeaterContainer = event.target.closest('.optionino-repeater-field').querySelector('.optionino-repeater-container');
            const template = repeaterContainer.querySelector('.optionino-repeater-item');

            let newItem;
            if (template) {
                const index = repeaterContainer.querySelectorAll('.optionino-repeater-item').length;
                newItem = template.cloneNode(true);
                newItem.querySelectorAll('input, textarea, select').forEach(function(input) {
                    const idParts = input.id.split('_');
                    idParts.pop();
                    idParts.push(index);
                    input.id = idParts.join('_');

                    const nameParts = input.name.split('_');
                    nameParts.pop();
                    nameParts.push(index);
                    input.name = nameParts.join('_');
                    input.value = '';
                });
            } else {
                newItem = createRepeaterItem();
            }
            repeaterContainer.appendChild(newItem);
        }

        if (event.target.classList.contains('optionino-remove-repeater-item')) {
            event.preventDefault();
            const repeaterContainer = event.target.closest('.optionino-repeater-field').querySelector('.optionino-repeater-container');
            const repeaterItems = repeaterContainer.querySelectorAll('.optionino-repeater-item');
            if (repeaterItems.length > 1) {
                event.target.closest('.optionino-repeater-item').remove();
            }
        }
    });

    const fileFrames = {};
    document.querySelectorAll('.upload-image-button').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const identifier = button.getAttribute('data-image-field');

            if (fileFrames[identifier]) {
                fileFrames[identifier].open();
                return;
            }

            fileFrames[identifier] = wp.media.frames.fileFrame = wp.media({
                title: 'Choose a media to upload',
                button: {
                    text: 'select image'
                },
                multiple: false
            });

            fileFrames[identifier].on('select', function() {
                const attachment = fileFrames[identifier].state().get('selection').first().toJSON();
                const imageUrlInput = document.getElementById(identifier);
                if (imageUrlInput) {
                    imageUrlInput.value = attachment.url;
                }
                const previewImage = document.getElementById(identifier + '-preview');
                if (previewImage) {
                    previewImage.src = attachment.url;
                }
                wp.media.model.settings.post.id = wpMediaPostId;
            });
            fileFrames[identifier].open();
        });
    });

    jQuery(document).ready(function ($) {
        const colorFields = $('.optionino-color-selector');
        if (colorFields.length > 0) {
            colorFields.wpColorPicker();
        }
    });

});
