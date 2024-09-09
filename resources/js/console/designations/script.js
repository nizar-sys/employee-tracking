$(document).ready(() => {
    document.addEventListener("click", (e) => {
        const target = e.target.closest(".delete-record");
        if (target) {
            handleDeleteRecord(target);
        }
    });
});

const handleDeleteRecord = (target) => {
    const recordId = target.getAttribute("data-id");
    if (!recordId) {
        console.error("Record ID not found.");
        return;
    }

    const form = createDeleteForm(recordId);
    document.body.appendChild(form);

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        } else {
            form.remove();
        }
    });
};

const createDeleteForm = (recordId) => {
    const form = document.createElement("form");
    form.method = "POST";
    form.action = urlDelete.replace(":id", recordId);
    form.style.display = "none";

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement("input");
        csrfInput.type = "hidden";
        csrfInput.name = "_token";
        csrfInput.value = csrfToken.getAttribute("content");
        form.appendChild(csrfInput);
    }

    const methodInput = document.createElement("input");
    methodInput.type = "hidden";
    methodInput.name = "_method";
    methodInput.value = "DELETE";
    form.appendChild(methodInput);

    return form;
};
