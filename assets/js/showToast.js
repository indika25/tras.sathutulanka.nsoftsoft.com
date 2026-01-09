function showToast(type, message, position = 'top-end') {
    Swal.fire({
        toast: true,
        position: position,
        icon: type, // success, error, warning, info
        title: message,
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
}
