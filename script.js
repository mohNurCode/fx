// script.js - Full JavaScript with CRUD and Authentication

let currentFilter = 'all';

// Load currencies from API
function loadCurrencies() {
    fetch('api.php?action=get')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderTable(data.data, currentFilter);
                updateTimestamps();
            } else {
                console.error('Failed to load currencies:', data.error);
                showNotification('Failed to load data', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Network error', 'error');
        });
}

// Render table
function renderTable(currencies, filter = 'all') {
    const tbody = document.getElementById('tableBody');
    let filtered = currencies;
    
    if (filter !== 'all') {
        filtered = currencies.filter(item => item.currency_code === filter);
    }
    
    if (filtered.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="${currentUser.isAdmin ? 4 : 3}" style="text-align:center; padding:2rem; color:var(--text-muted);">
                    <i class="fas fa-search" style="margin-right:0.5rem;"></i> No currencies found
                </td>
            </tr>
        `;
        return;
    }
    
    let html = '';
    filtered.forEach((item) => {
        const code = item.currency_code;
        const flagClass = item.flag_class || 'fi-us';
        const symbol = item.symbol || '$';
        const colorClass = item.color || 'green';
        
        html += `
            <tr data-currency="${code}" data-id="${item.id}">
                <td>
                    <div class="currency-code">
                        <span class="fi ${flagClass}"></span>
                        <span>${code}</span>
                        ${item.label ? `<span class="badge-pair">${item.label}</span>` : ''}
                    </div>
                </td>
                <td class="rate-cell buy" style="text-align:center;position:relative;">
                    <div class="line-icons">
                        <span class="move-icon ${colorClass}">${symbol}</span>
                        <span class="move-icon ${colorClass}">${symbol}</span>
                        <span class="move-icon ${colorClass}">${symbol}</span>
                    </div>
                    <span class="rate-value">${parseFloat(item.buy_rate).toFixed(4)}</span>
                    <div class="tx-line-visible"></div>
                </td>
                <td class="rate-cell sell" style="text-align:center;position:relative;">
                    <div class="line-icons">
                        <span class="move-icon ${colorClass}">${symbol}</span>
                        <span class="move-icon ${colorClass}">${symbol}</span>
                        <span class="move-icon ${colorClass}">${symbol}</span>
                    </div>
                    <span class="rate-value">${parseFloat(item.sell_rate).toFixed(4)}</span>
                    <div class="tx-line-visible"></div>
                </td>
                ${currentUser.isAdmin ? `
                <td style="text-align:center;">
                    <button onclick="editCurrency(${item.id})" class="btn-action btn-edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteCurrency(${item.id})" class="btn-action btn-delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
                ` : ''}
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

// Add/Edit Currency
document.getElementById('currencyForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('editId').value;
    const data = {
        currency_code: document.getElementById('currencyCode').value.trim().toUpperCase(),
        currency_name: document.getElementById('currencyName').value.trim(),
        buy_rate: parseFloat(document.getElementById('buyRate').value),
        sell_rate: parseFloat(document.getElementById('sellRate').value),
        flag_class: document.getElementById('flagClass').value,
        symbol: document.getElementById('symbol').value,
        color: document.getElementById('color').value,
        label: document.getElementById('label').value
    };
    
    const url = id > 0 ? 'api.php?action=update' : 'api.php?action=add';
    const method = id > 0 ? 'PUT' : 'POST';
    
    if (id > 0) data.id = parseInt(id);
    
    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            closeModal();
            loadCurrencies();
            showNotification(id > 0 ? 'Currency updated!' : 'Currency added!', 'success');
        } else {
            showNotification('Error: ' + result.error, 'error');
        }
    })
    .catch(error => {
        showNotification('Network error', 'error');
    });
});

// Edit currency
function editCurrency(id) {
    // First get the currency data
    fetch('api.php?action=get')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const currency = data.data.find(c => c.id === id);
                if (currency) {
                    document.getElementById('editId').value = currency.id;
                    document.getElementById('currencyCode').value = currency.currency_code;
                    document.getElementById('currencyName').value = currency.currency_name || '';
                    document.getElementById('buyRate').value = currency.buy_rate;
                    document.getElementById('sellRate').value = currency.sell_rate;
                    document.getElementById('flagClass').value = currency.flag_class || 'fi-us';
                    document.getElementById('symbol').value = currency.symbol || '$';
                    document.getElementById('color').value = currency.color || 'green';
                    document.getElementById('label').value = currency.label || 'spot';
                    document.getElementById('modalTitle').textContent = 'Edit Currency';
                    document.getElementById('currencyModal').style.display = 'flex';
                }
            }
        });
}

// Delete currency
function deleteCurrency(id) {
    if (!confirm('Are you sure you want to delete this currency?')) return;
    
    fetch('api.php?action=delete', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            loadCurrencies();
            showNotification('Currency deleted!', 'success');
        } else {
            showNotification('Error: ' + result.error, 'error');
        }
    })
    .catch(error => {
        showNotification('Network error', 'error');
    });
}

// Modal functions
function openModal() {
    document.getElementById('editId').value = 0;
    document.getElementById('currencyForm').reset();
    document.getElementById('modalTitle').textContent = 'Add Currency';
    document.getElementById('currencyModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('currencyModal').style.display = 'none';
}

// Show notification
function showNotification(message, type = 'info') {
    const colors = {
        success: '#4caf84',
        error: '#e8745a',
        info: '#c9a84c'
    };
    
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 15px 25px;
        background: var(--bg-card);
        border: 1px solid ${colors[type]};
        border-radius: 12px;
        color: var(--text-primary);
        font-weight: 500;
        z-index: 9999;
        box-shadow: 0 10px 40px var(--shadow-color);
        backdrop-filter: blur(10px);
        animation: slideInUp 0.3s ease;
        max-width: 400px;
    `;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}" 
           style="color:${colors[type]}; margin-right: 10px;"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutDown 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Update timestamps
function updateTimestamps() {
    const now = new Date();
    const dateStr = now.toISOString().slice(0, 10);
    const timeStr = now.toTimeString().slice(0, 5);
    
    document.getElementById('effectiveDate').textContent = dateStr;
    document.getElementById('footerTimestamp').textContent = `${dateStr} ${timeStr}`;
    document.getElementById('lastUpdate').textContent = 'just now';
}

// Refresh data
document.getElementById('refreshBtn')?.addEventListener('click', function() {
    loadCurrencies();
    this.style.transform = 'rotate(25deg)';
    setTimeout(() => { this.style.transform = 'rotate(0deg)'; }, 250);
});

// Filter
document.getElementById('currencyFilter')?.addEventListener('change', function() {
    currentFilter = this.value;
    loadCurrencies();
});

// Theme toggle
document.getElementById('themeToggle')?.addEventListener('click', function() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    if (currentTheme === 'light') {
        document.documentElement.removeAttribute('data-theme');
        this.innerHTML = '<i class="fas fa-moon"></i> <span id="themeLabel">Dark</span>';
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
        this.innerHTML = '<i class="fas fa-sun"></i> <span id="themeLabel">Light</span>';
    }
});

// Add currency button
document.getElementById('addCurrencyBtn')?.addEventListener('click', openModal);

// Close modal on outside click
document.getElementById('currencyModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Animation keyframes
const styleSheet = document.createElement('style');
styleSheet.textContent = `
    @keyframes slideInUp {
        from { transform: translateY(100px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes slideOutDown {
        from { transform: translateY(0); opacity: 1; }
        to { transform: translateY(100px); opacity: 0; }
    }
    .btn-action {
        padding: 5px 10px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        margin: 0 3px;
        transition: all 0.2s ease;
    }
    .btn-edit {
        background: rgba(201,168,76,0.2);
        color: var(--gold);
    }
    .btn-edit:hover {
        background: rgba(201,168,76,0.3);
        transform: scale(1.1);
    }
    .btn-delete {
        background: rgba(232,116,90,0.2);
        color: var(--rate-sell);
    }
    .btn-delete:hover {
        background: rgba(232,116,90,0.3);
        transform: scale(1.1);
    }
`;
document.head.appendChild(styleSheet);

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadCurrencies();
    
    // Auto-refresh every 60 seconds
    setInterval(loadCurrencies, 60000);
});