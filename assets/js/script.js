// ডিলিট করার আগে কনফার্মেশন চাওয়া
function confirmDelete() {
    return confirm("Are you sure you want to delete this item?");
}

// ফর্ম সাবমিট হলে বাটন ডিজেবল করা (ডাবল ক্লিক রোধ করতে)
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        let btn = this.querySelector('button[type="submit"]');
        if(btn) btn.disabled = true;
    });
});