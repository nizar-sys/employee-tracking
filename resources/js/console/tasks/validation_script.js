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
                    if (
                        e.element.parentElement.classList.contains(
                            "input-group"
                        )
                    ) {
                        e.element.parentElement.insertAdjacentElement(
                            "afterend",
                            e.messageElement
                        );
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

    validateForm("#taskForm", {
        employee_id: {
            validators: {
                notEmpty: {
                    message: "Please enter the employee ID",
                },
            },
        },
        title: {
            validators: {
                notEmpty: {
                    message: "Please enter the title",
                },
            },
        },
        date: {
            validators: {
                notEmpty: {
                    message: "Please select the date",
                },
            },
        },
        longlat: {
            validators: {
                notEmpty: {
                    message: "Please enter the longlat",
                },
            },
        },
        type: {
            validators: {
                notEmpty: {
                    message: "Please enter the type",
                },
            },
        },
        description: {
            validators: {
                notEmpty: {
                    message: "Please select the description",
                },
            },
        },
    });
});
