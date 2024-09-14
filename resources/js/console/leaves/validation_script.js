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

    validateForm("#leaveForm", {
        employee_id: {
            validators: {
                notEmpty: {
                    message: "Please enter the employee ID",
                },
            },
        },
        leave_type: {
            validators: {
                notEmpty: {
                    message: "Please enter the leave type",
                },
            },
        },
        start_date: {
            validators: {
                notEmpty: {
                    message: "Please select the start date",
                },
            },
        },
        end_date: {
            validators: {
                notEmpty: {
                    message: "Please select the end date",
                },
            },
        },
        reason: {
            validators: {
                notEmpty: {
                    message: "Please enter the reason",
                },
                stringLength: {
                    min: 5,
                    message: "Reason must be at least 5 characters long",
                },
            },
        },
        document: {
            validators: {
                file: {
                    extension: 'jpg,jpeg,png,pdf',
                    type: 'image/jpeg,image/png,application/pdf',
                    message: 'Please upload a valid document (JPG, PNG, PDF)',
                },
            },
        },
        status: {
            validators: {
                notEmpty: {
                    message: "Please select the leave status",
                },
            },
        },
    });
});
