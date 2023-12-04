document.addEventListener("DOMContentLoaded", function() {
    let tabContents = document.querySelectorAll('.tabcontent');
    let tabLinks = document.querySelectorAll('.tablinks');
    if (tabContents.length > 0 && tabLinks.length > 0) {
        tabContents[0].style.display = "block";
        tabLinks[0].classList.add("active");
    } else {
        console.error("Elements with class .tabcontent or .tablinks not found.");
    }
    //form ajax
    let SDO = document.getElementById('sdo');
    let form = document.getElementById('save-options-sdo');
    let errorText = form.querySelector('.error-text');
    let successText = form.querySelector('.success-text');
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        SDO.classList.add('loading');
        errorText.style.display = 'none';
        successText.style.display = 'none';
        let formData = new FormData(form);
        let checkboxes = form.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            let checkboxName = checkbox.name;
            if (!formData.has(checkboxName)) {
                formData.set(checkboxName, 'off');
            }
        });
        let repeaterFields = document.querySelectorAll('.sdo-repeater-field[data-repeater-name]');
        repeaterFields.forEach(repeaterField => {
            let repeaterName = repeaterField.getAttribute('data-repeater-name');
            let repeaterValues = collectRepeaterValues(repeaterField);
            formData.append(repeaterName, repeaterValues);
            console.log(repeaterValues);
        });
        formData.append('action', 'save_sdo_data');
        formData.append('security', data_sdo.nonce);
        let xhr = new XMLHttpRequest();
        xhr.open('POST', data_sdo.ajax_url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onload = function () {
            let response = JSON.parse(xhr.responseText);
            if (xhr.status === 200) {
                SDO.classList.remove('loading');
                if (response.success == true) {
                    successText.textContent = response.data.message;
                    successText.style.display = 'block';
                } else {
                    errorText.textContent = response.data.message;
                    errorText.style.display = 'block';
                }
            } else {
                console.error('Request failed with status:', xhr.status);
            }
        };
        xhr.onerror = function () {
            console.error('Request failed');
        };
        xhr.send(formData);
    });
    function collectRepeaterValues(repeaterField) {
        let repeaterItems = repeaterField.querySelectorAll('.sdo-repeater-item');
        let mainArray = [];
        repeaterItems.forEach((repeaterItem, index) => {
            let subArray = {};
            repeaterItem.querySelectorAll('input, textarea, select').forEach(input => {
                subArray[input.name.replace(/_\d+$/, '').replace(/^_/, '')] = input.value;
            });
            mainArray.push(encodeRepeaterValues(subArray));
        });
        return encodeRepeaterValues(mainArray);
    }
    function encodeRepeaterValues(values) {
        let params = new URLSearchParams();
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
});
function openTabSDO(evt, tabName) {
    let i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}
document.addEventListener("DOMContentLoaded", function () {
    const fields = Array.from(document.querySelectorAll('.sdo-box-option'));
    const fieldsWithRequire = Array.from(document.querySelectorAll('[data-require-0]'));
    function checkConditions(field,operator,requiredValue) {
        let requiredField = document.getElementById(field);

        if (!requiredField) {
            return false;
        }

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
                    let returnVal = false;
                    requiredValue.forEach(subVal => {
                        if (value == subVal) {
                            returnVal = true;
                        }
                    });
                    return returnVal;
                }
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
            if (!checkConditions(condition[0],condition[1],condition[2])) {
                field.setAttribute('display', "false");
            }
        });
    }
    function updateConditionalOptionsDisplay() {
        const conditionalOptions = Array.from(document.querySelectorAll('.sdo-conditional-option'));
        conditionalOptions.forEach(option => {
            const attributeValue = option.getAttribute('display');
            if (attributeValue === 'true') {
                option.style.display = 'flex';
            } else {
                option.style.display = 'none';
            }
        });
    }
    function updateOnLoadAndChange() {
        fieldsWithRequire.forEach(updateFieldVisibility);
        updateConditionalOptionsDisplay();
    }
    updateOnLoadAndChange();
    fields.forEach(input => input.addEventListener('change', updateOnLoadAndChange));
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('sdo-add-repeater-item')) {
            event.preventDefault();
            let repeaterContainer = event.target.closest('.sdo-repeater-field').querySelector('.sdo-repeater-container');
            let template = repeaterContainer.querySelector('.sdo-repeater-item');
            if (template) {
                let index = repeaterContainer.querySelectorAll('.sdo-repeater-item').length;
                let newItem = template.cloneNode(true);
                newItem.querySelectorAll('input, textarea, select').forEach(function (input) {
                    let idParts = input.id.split('_');
                    idParts.pop();
                    idParts.push(index);
                    input.id = idParts.join('_');
                    let nameParts = input.name.split('_');
                    nameParts.pop();
                    nameParts.push(index);
                    input.name = nameParts.join('_');
                    input.value = '';
                });
                repeaterContainer.appendChild(newItem);
            } else {
                let newItem = document.createElement('div');
                newItem.classList.add('sdo-repeater-item');
                newItem.innerHTML = '<input type="text" name="new-field" value="">';
                repeaterContainer.appendChild(newItem);
            }
        }
        if (event.target.classList.contains('sdo-remove-repeater-item')) {
            event.preventDefault();
            let repeaterContainer = event.target.closest('.sdo-repeater-field').querySelector('.sdo-repeater-container');
            let repeaterItems = repeaterContainer.querySelectorAll('.sdo-repeater-item');
            if (repeaterItems.length > 1) {
                event.target.closest('.sdo-repeater-item').remove();
            }
        }
    });
    let fileFrames = {};
    document.querySelectorAll('.upload-image-button').forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            let identifier = button.getAttribute('data-image-field');
            if (fileFrames[identifier]) {
                fileFrames[identifier].open();
                return;
            }
            fileFrames[identifier] = wp.media.frames.fileFrame = wp.media({
                title: 'انتخاب یا آپلود تصویر',
                button: {
                    text: 'انتخاب این تصویر'
                },
                multiple: false
            });
            fileFrames[identifier].on('select', function () {
                var attachment = fileFrames[identifier].state().get('selection').first().toJSON();
                let imageUrlInput = document.getElementById(identifier);
                if (imageUrlInput) {
                    imageUrlInput.value = attachment.url;
                }
                let previewImage = document.getElementById(identifier + '-preview');
                if (previewImage) {
                    previewImage.src = attachment.url;
                }
                wp.media.model.settings.post.id = wpMediaPostId;
            });
            fileFrames[identifier].open();
        });
    });
    let colorField = document.querySelectorAll('.sdo-color-selector');
    if (colorField) {
        let colorPicker = new wp.ColorPicker(colorField);
    }
});