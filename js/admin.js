/*
*  ------------------ Logout Side Menu ----------------
*/
document.addEventListener('DOMContentLoaded', () => {
    const logoutBtn = document.getElementById('logout-button');
    logoutBtn.addEventListener('click', () => {
        const logout = document.getElementById('logout-form');
        logout.submit();
    });
});
/*
*  ------------------ End of Logout Side Menu -------------------
*/



/*
*  ------------------- Close Side Bar Menu -----------------------
*/
document.addEventListener('DOMContentLoaded', () => {
    const menuBar = document.querySelector('.top-bar nav .bx.bx-menu');
    const sideBar = document.querySelector('.sidebar');
    const section = document.querySelector('section');

    menuBar.addEventListener('click', () => {
        sideBar.classList.toggle('close');

        if (sideBar.classList.contains('close')) {
            section.style.width = "calc(100% - 60px)";
            section.style.left = "60px";
        } else {
            section.style.width = "calc(100% - 230px)";
            section.style.left = "230px";
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth < 768) {
            sideBar.classList.add('close');
            section.style.width = "calc(100% - 60px)";
            section.style.left = "60px";
        }
        else {
            sideBar.classList.remove('close');
            section.style.width = "calc(100% - 230px)";
            section.style.left = "230px";
        }
    });
});
/*
*  --------------------- End of Close Side Bar Menu -----------------------
*/




/*
*  ----------------------- Dark Mode ---------------------------------
*/
document.addEventListener('DOMContentLoaded', () => {
    const toggler = document.getElementById('theme-toggle');

    toggler.addEventListener('change', () => {
        const isChecked = toggler.checked;
        if (isChecked) {
            document.body.classList.add('dark');
        }
        else {
            document.body.classList.remove('dark');
        }
    });
});
/*
*  ------------------------- End of Dark Mode -------------------------
*/




/*
/*
*  ------------------------------ Fixed Dropdown Function ---------------------
*/
/*
*  ------------------------------ Fully Fixed Dropdown Function ---------------------
*/
    document.addEventListener('DOMContentLoaded', () => {
        const dropdowns = document.querySelectorAll('.dropdown');

        dropdowns.forEach(dropdown => {
            const select = dropdown.querySelector('.select');
            const caret = dropdown.querySelector('.caret');
            const menu = dropdown.querySelector('.menu');
            const options = dropdown.querySelectorAll('.menu li');
            const selected = dropdown.querySelector('.selected');
            const hiddenInput = dropdown.querySelector('input[type="hidden"]');

            if (!select || !caret || !menu || !selected || !hiddenInput) return;

            // Toggle dropdown open/close
            select.addEventListener('click', (e) => {
                e.stopPropagation();

                // Close other dropdowns
                dropdowns.forEach(dd => {
                    if (dd !== dropdown) {
                        dd.querySelector('.menu')?.classList.remove('menu-open');
                        dd.querySelector('.caret')?.classList.remove('caret-rotate');
                        dd.querySelector('.select')?.classList.remove('select-clicked');
                        dd.classList.remove('active');
                    }
                });

                // Toggle this dropdown
                select.classList.toggle('select-clicked');
                caret.classList.toggle('caret-rotate');
                menu.classList.toggle('menu-open');
                dropdown.classList.toggle('active');
            });

            // Select an option
            options.forEach(option => {
                option.addEventListener('click', (e) => {
                    e.stopPropagation();

                    const value = option.getAttribute('data-value');
                    const text = option.textContent.trim();

                    // Set selected text and hidden input value
                    selected.textContent = text;
                    hiddenInput.value = value;

                    // Close dropdown
                    select.classList.remove('select-clicked');
                    caret.classList.remove('caret-rotate');
                    menu.classList.remove('menu-open');
                    dropdown.classList.remove('active');

                    // Highlight selected option
                    options.forEach(opt => opt.classList.remove('active'));
                    option.classList.add('active');
                });
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(e.target)) {
                    dropdown.querySelector('.menu')?.classList.remove('menu-open');
                    dropdown.querySelector('.caret')?.classList.remove('caret-rotate');
                    dropdown.querySelector('.select')?.classList.remove('select-clicked');
                    dropdown.classList.remove('active');
                }
            });
        });
    });
/*
*  --------------------- End of Fully Fixed Dropdown Function ------------------------
*/







/*
*  -------------------- Function for Success Message ---------------------------
*/
document.addEventListener('DOMContentLoaded', () => {
    const successMessage = document.querySelector('.success-message');

    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = '0';
            successMessage.remove();
        }, 3000);
    }
});
/*
*  -------------------------- End of Function for Success Message -------------------
*/





/*
*  -------------------- Function for Warning Message ---------------------------
*/
document.addEventListener('DOMContentLoaded', () => {
    const warningMessage = document.querySelector('.warning-message');

    if (warningMessage) {
        setTimeout(() => {
            warningMessage.style.opacity = '0';
            warningMessage.remove();
        }, 3000);
    }
});
/*
*  -------------------------- End of Function for Warning Message -------------------
*/





/*
*  --------------------- Function for Error Message ------------------------------
*/
document.addEventListener('DOMContentLoaded', () => {
    const errorMessage = document.querySelector('.error-message');

    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.opacity = '0';
            errorMessage.remove();
        }, 3000);
    }
});
/*
*  ------------------------- End of Function for Error Message -------------------------
*/





/*
*  -------------------------- Function for Delete Confirmation Pop Up ---------------------
*/
document.addEventListener('DOMContentLoaded', () => {
    const deleteBtn = document.querySelector('.delete-button-popup');
    const confirmationPopup = document.getElementById('deletePopup');
    const closeBtn = document.querySelector('.close-popup');
    const confirmDeleteBtn = document.querySelector('.confirm-delete');
    const deleteForm = document.getElementById('deleteForm');
    const visibility = 'open-popup';

    deleteBtn.addEventListener('click', () => {
        confirmationPopup.classList.add(visibility);
    });

    closeBtn.addEventListener('click', () => {
        confirmationPopup.classList.remove(visibility);
    });

    confirmDeleteBtn.addEventListener('click', () => {
        deleteForm.submit();
    });
});
/*
*  ------------------- End of Function for Delete Confirmation Popup --------------------------
*/




/*
*  -------------------------- Function to Click an Icon for Search ---------------------
*/
document.addEventListener('DOMContentLoaded', () => {
    const searchBtn = document.getElementById('search-button');

    searchBtn.addEventListener('click', () => {
        const search = document.getElementById('search-form');
        search.submit();
    });
});
/*
*  ---------------------- End of Function to Click an Icon for Search -----------------------
*/




/*
*  ------------- Drag and Drop Function -----------------
*/
document.addEventListener('DOMContentLoaded', () => {
    const dragArea = document.querySelector('.drag-area');
    const dragText = dragArea.querySelector('.drag-text');
    const imageInput = dragArea.querySelector('.select-image-input');
    let file;

    dragArea.addEventListener('dragover', (event) => {
        event.preventDefault();
        dragArea.classList.add('active');
        dragText.textContent = 'Release to upload file';
    });

    dragArea.addEventListener('dragleave', () => {
        dragArea.classList.remove('active');
        dragText.textContent = 'Drag and drop to upload image';
    });

    dragArea.addEventListener('drop', (event) => {
        event.preventDefault();
        dragArea.classList.remove('active');

        file = event.dataTransfer.files[0];

        //showImage();
    });

    dragArea.addEventListener('click', () => {
        imageInput.click();
    });

    imageInput.addEventListener('change', () => {
        file = imageInput.files[0];
        //showImage();
    });

    function showImage() {
        let fileType = file.type;

        let validExtensions = ['image/jpg', 'image/jpeg', 'image/png', 'image/svg'];

        if (validExtensions.includes(fileType)) {
            let fileReader = new FileReader();
            fileReader.onload = () => {
                let fileUrl = fileReader.result;
                let imgTag = `<img src="${fileUrl}" alt="">`;
                dragArea.innerHTML = imgTag;
            }
            fileReader.readAsDataURL(file);
        }
    }
});
/*
* ----------------- End of Drag and Drop Function ------------------
*/