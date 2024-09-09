document.addEventListener("DOMContentLoaded", function () {
    const dropzone = document.querySelector(".dropzone-upload");

    if (dropzone) {
        const fileInput = dropzone.querySelector(
            '.dropzone-upload input[type="file"]'
        );
        const previewContainer = document.getElementById("dropzone-preview");
        const dropzoneMessage = dropzone.querySelector(".dz-message");
        const clickables = dropzone.querySelector(".needsclick");

        if (typeof existingFiles !== "undefined") {
            handleExistingFiles(existingFiles);
        }

        dropzone.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropzone.classList.add("dragover");
        });

        dropzone.addEventListener("dragleave", () => {
            dropzone.classList.remove("dragover");
        });

        dropzone.addEventListener("drop", (e) => {
            e.preventDefault();
            dropzone.classList.remove("dragover");
            handleFiles(e.dataTransfer.files);
        });

        clickables.addEventListener("click", () => {
            fileInput.click();
        });

        fileInput.addEventListener("change", (e) => {
            handleFiles(e.target.files);
        });

        function formatSize(bytes) {
            if (bytes === 0) return "0 B";
            const sizes = ["B", "KB", "MB", "GB", "TB"];
            const i = Math.floor(Math.log(bytes) / Math.log(1024));
            return (bytes / Math.pow(1024, i)).toFixed(2) + " " + sizes[i];
        }

        function handleFiles(files) {
            const dataTransfer = new DataTransfer();

            Array.from(files).forEach((file) => {
                const reader = new FileReader();

                reader.onload = (e) => {
                    const preview = document.createElement("div");
                    preview.className = "dz-preview dz-file-preview mb-3";
                    preview.innerHTML = `
                        <div class="dz-details">
                            <div class="dz-thumbnail">
                                <img src="${e.target.result}" alt="${
                        file.name
                    }" />
                            </div>
                            <div class="dz-filename">${file.name}</div>
                            <div class="dz-size">${formatSize(file.size)}</div>
                            <a class="dz-remove" href="#" data-dz-remove>Remove file</a>
                        </div>
                    `;

                    previewContainer.appendChild(preview);
                    dropzoneMessage.style.display = "none";

                    preview
                        .querySelector(".dz-remove")
                        .addEventListener("click", () => {
                            previewContainer.removeChild(preview);
                            removeFile(file);
                            if (previewContainer.children.length === 0) {
                                dropzoneMessage.style.display = "block";
                            }
                        });
                };

                reader.readAsDataURL(file);
                dataTransfer.items.add(file);
            });

            fileInput.files = dataTransfer.files;
        }

        function handleExistingFiles(fileUrls) {
            const urls = Array.isArray(fileUrls) ? fileUrls : [fileUrls];

            urls.forEach(async (url) => {
                const fileName = url.split("/").pop();
                url = assetUrl + url;

                try {
                    const size = await fetchFileSize(url);
                    const formattedSize = formatSize(size);

                    const preview = document.createElement("div");
                    preview.className = "dz-preview dz-file-preview mb-3";
                    preview.innerHTML = `
                        <div class="dz-details">
                            <div class="dz-thumbnail">
                                <img src="${url}" alt="${fileName}" />
                            </div>
                            <div class="dz-filename">${fileName}</div>
                            <div class="dz-size">${formattedSize}</div>
                            <a class="dz-remove" href="#" data-dz-remove>Remove file</a>
                        </div>
                    `;

                    previewContainer.appendChild(preview);
                    dropzoneMessage.style.display = "none";

                    preview
                        .querySelector(".dz-remove")
                        .addEventListener("click", () => {
                            previewContainer.removeChild(preview);
                            removeFile({ name: fileName });
                            if (previewContainer.children.length === 0) {
                                dropzoneMessage.style.display = "block";
                            }
                        });
                } catch (error) {
                    console.error("Error fetching file size:", error);
                }
            });
        }

        function fetchFileSize(url) {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open("HEAD", url, true);
                xhr.onload = () => {
                    if (xhr.status === 200) {
                        const size = parseInt(
                            xhr.getResponseHeader("Content-Length"),
                            10
                        );
                        resolve(size);
                    } else {
                        reject(new Error("Failed to get file size"));
                    }
                };
                xhr.onerror = () => reject(new Error("Request failed"));
                xhr.send();
            });
        }

        function removeFile(fileToRemove) {
            const dataTransfer = new DataTransfer();
            Array.from(fileInput.files).forEach((file) => {
                if (file.name !== fileToRemove.name) {
                    dataTransfer.items.add(file);
                }
            });
            fileInput.files = dataTransfer.files;
        }
    }
});
