import './bootstrap';

// SPK Application JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.classList.contains('alert-dismissible')) {
                const closeBtn = alert.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.click();
                }
            }
        }, 5000);
    });

    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi.');
            }
        });
    });

    // Auto-calculate total scores
    const scoreInputs = document.querySelectorAll('input[type="number"]');
    scoreInputs.forEach(input => {
        input.addEventListener('input', function() {
            const form = this.closest('form');
            if (form) {
                calculateTotal(form);
            }
        });
    });

    // Radio button groups for surveys
    const radioGroups = document.querySelectorAll('input[type="radio"]');
    radioGroups.forEach(radio => {
        radio.addEventListener('change', function() {
            const form = this.closest('form');
            if (form) {
                calculateTotal(form);
            }
        });
    });
});

function calculateTotal(form) {
    const totalDisplay = form.querySelector('#totalScore');
    if (!totalDisplay) return;

    let total = 0;
    
    // For academic scores
    const academicInputs = form.querySelectorAll('input[name^="mathematics"], input[name^="indonesian"], input[name^="english"], input[name^="science"], input[name^="social_studies"]');
    if (academicInputs.length > 0) {
        academicInputs.forEach(input => {
            if (input.value) {
                total += parseFloat(input.value) || 0;
            }
        });
        totalDisplay.textContent = (total / academicInputs.length).toFixed(2);
        return;
    }

    // For interest survey
    const interestInputs = form.querySelectorAll('input[name^="answers"]');
    if (interestInputs.length > 0) {
        interestInputs.forEach(input => {
            if (input.checked) {
                total += parseInt(input.value);
            }
        });
        totalDisplay.textContent = total;
        return;
    }

    // For interview scores
    const interviewInputs = form.querySelectorAll('input[name^="communication_skill"], input[name^="motivation"], input[name^="personality"], input[name^="academic_potential"], input[name^="career_orientation"]');
    if (interviewInputs.length > 0) {
        interviewInputs.forEach(input => {
            if (input.checked) {
                total += parseInt(input.value);
            }
        });
        totalDisplay.textContent = total;
        
        // Change color based on score
        if (total >= 20) {
            totalDisplay.className = 'text-success';
        } else if (total >= 15) {
            totalDisplay.className = 'text-warning';
        } else {
            totalDisplay.className = 'text-danger';
        }
        return;
    }
}

// Chart initialization
function initCharts() {
    // Major distribution chart
    const majorCtx = document.getElementById('majorChart');
    if (majorCtx) {
        const majorChart = new Chart(majorCtx, {
            type: 'doughnut',
            data: {
                labels: ['IPA', 'IPS'],
                datasets: [{
                    data: [window.ipaCount || 0, window.ipsCount || 0],
                    backgroundColor: ['#28a745', '#17a2b8'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Ranking chart
    const rankingCtx = document.getElementById('rankingChart');
    if (rankingCtx && window.rankingData) {
        const rankingChart = new Chart(rankingCtx, {
            type: 'bar',
            data: {
                labels: window.rankingData.map(item => item.name.substring(0, 10) + '...'),
                datasets: [{
                    label: 'Skor Akhir',
                    data: window.rankingData.map(item => item.score),
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
}

// Initialize charts when DOM is ready
document.addEventListener('DOMContentLoaded', initCharts);
