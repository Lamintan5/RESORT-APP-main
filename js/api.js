async function fetchResorts() {
    try {
        const response = await fetch('fetch_resorts.php');
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const result = await response.json();
        if (result.success) {
            const tableBody = document.querySelector('#resorts-table tbody');
            tableBody.innerHTML = '';
            result.data.forEach(resort => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${resort.id}</td>
                    <td>${resort.title}</td>
                    <td>${resort.location}</td>
                    <td>${resort.description}</td>
                    <td>${resort.status}</td>
                    <td>${resort.price}</td>
                    <td>
                        <button class="action-btn edit-btn" data-id="${resort.id}" title="Edit Resort"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="action-btn delete-btn" data-id="${resort.id}" title="Delete Resort"><i class="fa-solid fa-trash"></i></button>
                        <button class="action-btn add-amenity-btn" data-id="${resort.id}" title="Add Amenity"><i class="fa-solid fa-plus"></i></button>
                    </td>
                `;
                tableBody.appendChild(row);
            });


            document.querySelector('#resorts-table').addEventListener('click', event => {

                const editBtn = event.target.closest('.edit-btn');
                if (editBtn) {
                    const id = editBtn.getAttribute('data-id');
                    editResort(id);
                }

                const deleteBtn = event.target.closest('.delete-btn');
                if (deleteBtn) {
                    const id = deleteBtn.getAttribute('data-id');
                    deleteResort(id);
                }

                const amenityBtn = event.target.closest('.add-amenity-btn');
                if (amenityBtn) {
                    const id = amenityBtn.getAttribute('data-id');
                    openAmenityModal(id);
                }
            });

        } else {
            alert(`Error fetching resorts: ${result.message}`);
        }
    } catch (error) {
        console.error('Error fetching resorts:', error);
    }
}

fetchResorts();

function openModal() {
    document.getElementById('addResortModel').style.display = 'flex';
}

function closeModal() {
    document.getElementById('addResortModel').style.display = 'none';
}

document.getElementById('addResortForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    try {
        const response = await fetch('add_resort.php', {
            method: 'POST',
            body: formData,
        });
        const result = await response.json();
        if (result.success) {
            alert('Resort added successfully');
            closeModal();
            fetchResorts();
        } else {
            alert(`Error: ${result.message}`);
        }
    } catch (error) {
        console.error('Error adding Resort:', error);
    }
});

function openAmenityModal(resortId) {
    document.getElementById('amenity_resort_id').value = resortId;
    document.getElementById('addAmenityModal').style.display = 'flex';
}

function closeAmenityModal() {
    document.getElementById('addAmenityModal').style.display = 'none';
    document.getElementById('addAmenityForm').reset();
}

document.getElementById('addAmenityForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    try {
        const response = await fetch('add_amenity.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if(result.success) {
            alert('Amenity added successfully!');
            closeAmenityModal();
        } else {
            alert(`Error: ${result.message}`);
        }
    } catch (error) {
        console.error('Error adding Amenity:', error);
    }
});

async function deleteResort(id) {
    if (!confirm('Are you sure you want to delete this resort?')) return;
    try {
        const response = await fetch('delete_resort.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`,
        });
        const result = await response.json();
        if (result.success) {
            alert('Resort deleted successfully');
            fetchResorts();
        } else {
            alert(`Error deleting resort: ${result.message}`);
        }
    } catch (error) {
        console.error('Error deleting resort:', error);
    }
}

function openEditModal(resort) {
    document.getElementById('edit-id').value = resort.id;
    document.getElementById('edit-title').value = resort.title;
    document.getElementById('edit-location').value = resort.location;
    document.getElementById('edit-price').value = resort.price;
    document.getElementById('edit-description').value = resort.description;
    document.getElementById('editResortModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editResortModal').style.display = 'none';
}

document.getElementById('editResortForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    try {
        const response = await fetch('update_resort.php', {
            method: 'POST',
            body: formData,
        });
        const result = await response.json();
        if (result.success) {
            alert('Resort updated successfully');
            closeEditModal();
            fetchResorts();
        } else {
            alert(`Error: ${result.message}`);
        }
    } catch (error) {
        console.error('Error updating resort:', error);
    }
});

async function editResort(id) {
    try {
        const response = await fetch(`fetch_single_resort.php?id=${id}`);
        const result = await response.json();
        if (result.success) {
            openEditModal(result.data);
        } else {
            alert(`Error fetching resort details: ${result.message}`);
        }
    } catch (error) {
        console.error('Error fetching resort details:', error);
    }
}