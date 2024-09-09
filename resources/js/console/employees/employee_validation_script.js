document.addEventListener("DOMContentLoaded", () => {
    const validateForm = (formSelector, fieldsConfig) => {
        const formElement = document.querySelector(formSelector);
        if (!formElement) return;

        FormValidation.formValidation(formElement, {
            fields: fieldsConfig,
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: "",
                    rowSelector: ".form-floating",
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                autoFocus: new FormValidation.plugins.AutoFocus(),
            },
            init: (instance) => {
                instance.on("plugins.message.placed", (e) => {
                    if (e.element.parentElement.classList.contains("input-group")) {
                        e.element.parentElement.insertAdjacentElement("afterend", e.messageElement);
                    }
                });

                instance.on("core.element.validated", (e) => {
                    if (e.valid) {
                        e.element.classList.add("is-valid");
                    } else {
                        e.element.classList.remove("is-valid");
                    }
                });

                instance.on("core.form.valid", () => {
                    formElement.submit();
                });
            },
        });
    };

    var configValidation = {
        number: {
            validators: {
                notEmpty: { message: "Please enter the employee number" },
                stringLength: { min: 3, message: "Number must be at least 3 characters long" },
            },
        },
        designation_id: {
            validators: { notEmpty: { message: "Please select a designation" } },
        },
        user_id: {
            validators: { notEmpty: { message: "Please select user detail" } },
        },
        phone: {
            validators: {
                notEmpty: { message: "Please enter the phone number" },
                regexp: { regexp: /^[0-9]+$/, message: "Phone number must be numeric" },
            },
        },
        address: {
            validators: { notEmpty: { message: "Please enter the address" } },
        },
        zip_code: {
            validators: {
                notEmpty: { message: "Please enter the zip code" },
                regexp: { regexp: /^[0-9]{5}$/, message: "Zip code must be 5 digits" },
            },
        },
        date_of_birth: {
            validators: { notEmpty: { message: "Please enter the date of birth" } },
        },
        work_hour: {
            validators: { notEmpty: { message: "Please enter the work hour" } },
        },
    };

    if (typeof existingFiles == "undefined") {
        configValidation.photo = {
            validators: { notEmpty: { message: "Please upload a profile picture" } },
        };
    }


    validateForm("#employeeForm", configValidation);
});
