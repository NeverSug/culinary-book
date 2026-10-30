import 'bootstrap';

document.querySelectorAll('.like-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const res = await fetch(btn.dataset.url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });

        if (!res.ok) return;
        const data = await res.json();

        btn.querySelector('.like-count').textContent = data.count;
        btn.querySelector('.like-icon').textContent = data.liked ? '♥' : '♡';
        btn.classList.toggle('btn-danger', data.liked);
        btn.classList.toggle('btn-outline-danger', !data.liked);
    });
});

