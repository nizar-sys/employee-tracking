document.addEventListener("DOMContentLoaded", () => {
    const createFieldsConfig = (type) => {
        const baseConfig = {
            employee_id: {
                validators: {
                    notEmpty: {
                        message: "Please select an employee",
                    },
                },
            },
            date: {
                validators: {
                    notEmpty: {
                        message: "Please enter the date",
                    },
                    date: {
                        format: "YYYY-MM-DD",
                        message: "The date is not valid",
                    },
                },
            },
            attendance_type: {
                validators: {
                    notEmpty: {
                        message: "Please select an attendance type",
                    },
                },
            },
        };

        const specificConfig =
            type === "check_in"
                ? {
                      check_in: {
                          validators: {
                              notEmpty: {
                                  message: "Please enter the clock-in time",
                              },
                              regexp: {
                                  regexp: /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/,
                                  message:
                                      "Please enter a valid time in 24-hour format (HH:MM)",
                              },
                          },
                      },
                      location_check_in: {
                          validators: {
                              notEmpty: {
                                  message: "Please enter the clock-in location",
                              },
                              stringLength: {
                                  min: 5,
                                  message:
                                      "Location must be at least 5 characters long",
                              },
                          },
                      },
                      longlat_check_in: {
                          validators: {
                              notEmpty: {
                                  message:
                                      "Please enter the longitude/latitude for clock-in",
                              },
                              regexp: {
                                  regexp: /^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/,
                                  message:
                                      "Please enter a valid longitude/latitude format (e.g., 37.7749,-122.4194)",
                              },
                          },
                      },
                      picture_check_in: {
                          validators: {
                              file: {
                                  extension: "jpeg,jpg,png",
                                  type: "image/jpeg,image/png",
                                  message:
                                      "Please upload a valid image file (JPEG or PNG)",
                              },
                          },
                      },
                  }
                : type === "check_out"
                ? {
                      check_out: {
                          validators: {
                              notEmpty: {
                                  message: "Please enter the clock-out time",
                              },
                              regexp: {
                                  regexp: /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/,
                                  message:
                                      "Please enter a valid time in 24-hour format (HH:MM)",
                              },
                          },
                      },
                      location_check_out: {
                          validators: {
                              notEmpty: {
                                  message:
                                      "Please enter the clock-out location",
                              },
                              stringLength: {
                                  min: 5,
                                  message:
                                      "Location must be at least 5 characters long",
                              },
                          },
                      },
                      longlat_check_out: {
                          validators: {
                              notEmpty: {
                                  message:
                                      "Please enter the longitude/latitude for clock-out",
                              },
                              regexp: {
                                  regexp: /^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/,
                                  message:
                                      "Please enter a valid longitude/latitude format (e.g., 37.7749,-122.4194)",
                              },
                          },
                      },
                      picture_check_out: {
                          validators: {
                              file: {
                                  extension: "jpeg,jpg,png",
                                  type: "image/jpeg,image/png",
                                  message:
                                      "Please upload a valid image file (JPEG or PNG)",
                              },
                          },
                      },
                  }
                : {};

        return { ...baseConfig, ...specificConfig };
    };

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
                    e.element.classList.toggle("is-valid", e.valid);
                });

                instance.on("core.form.valid", () => {
                    formElement.submit();
                });
            },
        });
    };

    const attendanceTypeElement = document.getElementById("attendance-type");
    const checkInFields = $("#check-in-fields");
    const checkOutFields = $("#check-out-fields");

    const updateFormValidation = () => {
        const type = attendanceTypeElement.value;
        const fieldsConfig = createFieldsConfig(type);

        validateForm("#attendanceForm", fieldsConfig);

        if (type === "check_in") {
            checkInFields.show();
            checkOutFields.hide();
        } else if (type === "check_out") {
            checkInFields.hide();
            checkOutFields.removeClass('d-none').show();
        }
    };

    updateFormValidation();

    attendanceTypeElement.addEventListener("change", updateFormValidation);
});
