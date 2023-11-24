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
    form.addEventListener('submit', function(event) {
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
        formData.append('action', 'save_sdo_data');
        formData.append('security', data_sdo.nonce);
        let xhr = new XMLHttpRequest();
        xhr.open('POST', data_sdo.ajax_url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onload = function() {
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
        xhr.onerror = function() {
            console.error('Request failed');
        };
        xhr.send(formData);
    });
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

    fields.forEach((input) => {
        input.addEventListener('change', () => {
            fieldsWithRequire.forEach(updateFieldVisibility);
            updateConditionalOptionsDisplay();
        });
    });
});
