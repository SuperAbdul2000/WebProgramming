function validateEmail(email) {
    const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^\+[\d]{11}$/;
    return re.test(phone);
}

function formatDate(inputDate) {
    const dateParts = inputDate.split(':');
    if (dateParts.length !== 3) {
        return null;
    }

    const day = parseInt(dateParts[0], 10);
    const month = parseInt(dateParts[1], 10);
    const year = parseInt(dateParts[2], 10);

    if (
        isNaN(day) || isNaN(month) || isNaN(year) ||
        day < 1 || day > 31 || month < 1 || month > 12 || year < 1000
    ) {
        return null; 
    }

    const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    return formattedDate;
}

function getURLParameter(name) {
    const params = new URLSearchParams(window.location.search);
    return params.get(name);
}

function removeURLParameter(parameter) {
    const url = new URL(window.location.href);
    url.searchParams.delete(parameter); // Удаляем параметр
    window.history.replaceState(null, '', url.toString()); // Обновляем URL без перезагрузки страницы
}

document.addEventListener('DOMContentLoaded', function () {
    const successParam = getURLParameter('success');
    if (successParam === '1') {
        console.log('Параметр success найден, открываем модальное окно');
        const modal = new bootstrap.Modal(document.getElementById('modalSuccess'));
        modal.show();

        removeURLParameter('success');
    }

    const form = document.querySelector('form');

    if (form) {
        form.addEventListener('submit', function (event) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const service = document.getElementById('service').value.trim();
            const appointmentDateInput = document.getElementById('appointment_date').value.trim();

            if (!name || !email || !service || !appointmentDateInput) {
                alert('Пожалуйста, заполните все обязательные поля: Имя, Email, Услуга и Дата.');
                event.preventDefault();
                return;
            }

            if (!validateEmail(email)) {
                alert('Введите корректный email.');
                event.preventDefault();
                return;
            }

            if (phone && !validatePhone(phone)) {
                alert('Введите корректный номер телефона (начинается с + и содержит 11 цифр).');
                event.preventDefault();
                return;
            }

            const appointmentDate = formatDate(appointmentDateInput);
            if (!appointmentDate) {
                alert('Введите корректную дату в формате дд:мм:гггг.');
                event.preventDefault();
                return;
            }

            document.getElementById('appointment_date').value = appointmentDate;

            console.log('Форма успешно отправлена:', {
                name,
                email,
                phone,
                service,
                appointmentDate,
            });
        });
    }
});
