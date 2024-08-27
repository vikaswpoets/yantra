let yantra = {};

yantra.globalQueue = Promise.resolve();

yantra.processQueue = async function(queue) {
    await queue;
};

yantra.lib = class {
    executeScripts(content){
        const scriptRegex = /<script\b[^>]*>([\s\S]*?)<\/script>/gm;
        let match;
        while ((match = scriptRegex.exec(content)) !== null) {
            const scriptContent = match[1];
            const scriptElement = document.createElement('script');
            scriptElement.text = scriptContent;
            document.body.appendChild(scriptElement);
            document.body.removeChild(scriptElement);
        }
    }
}

yantra.Validator = class {
    validateRequired(value) {
        return value.trim() !== '';
    }

    validateEmail(value) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(value);
    }

    validateNumber(value) {
        return !isNaN(parseFloat(value)) && isFinite(value);
    }

    validateInteger(value) {
        return Number.isInteger(parseFloat(value));
    }

    validateURL(value) {
        try {
            new URL(value);
            return true;
        } catch (_) {
            return false;
        }
    }

    validateDate(value) {
        const date = new Date(value);
        return !isNaN(date.getTime());
    }

    validatePhone(value) {
        const phonePattern = /^\+?[1-9]\d{1,14}$/;
        return phonePattern.test(value);
    }

    validateMinLength(value, minLength) {
        return value.length >= minLength;
    }

    validateMaxLength(value, maxLength) {
        return value.length <= maxLength;
    }

    validateRange(value, min, max) {
        const numValue = parseFloat(value);
        return !isNaN(numValue) && numValue >= min && numValue <= max;
    }

    validatePattern(value, pattern) {
        return pattern.test(value);
    }

    validateAlpha(value) {
        const alphaPattern = /^[A-Za-z]+$/;
        return alphaPattern.test(value);
    }

    validateAlphanumeric(value) {
        const alphanumericPattern = /^[A-Za-z0-9]+$/;
        return alphanumericPattern.test(value);
    }

    validateCheckbox(field) {
        const checkboxes = field.form.querySelectorAll(`input[name="${field.name}"]:checked`);
        return checkboxes.length > 0;
    }

    validateSelect(field) {
        return field.value !== '';
    }

    validateFileType(files, allowedTypes) {
        return Array.from(files).every(file => allowedTypes.includes(file.type));
    }

    validateFileSize(files, maxSize) {
        return Array.from(files).every(file => file.size <= maxSize);
    }

    validateCreditCard(value) {
        const creditCardPattern = /^(\d{4}[- ]?){3}\d{4}$/;
        return creditCardPattern.test(value);
    }
};

yantra.AjaxHandler = class {
    constructor(waitForQueue = true, maxRetries = 3, timeout = 5000) {
        this.extraFields = {};
        this.eventListeners = {};
        this.localQueue = Promise.resolve(); // Local queue
        this.waitForQueue = waitForQueue; // Determines if the request should wait for both global and local queue
        this.maxRetries = maxRetries;
        this.timeout = timeout;
    }

    // Method to add or update extra fields
    updateExtraFields(fields) {
        this.extraFields = { ...this.extraFields, ...fields };
    }

    // Method to add event listeners
    addEventListener(event, callback) {
        this.eventListeners[event] = callback;
    }

    // Method to trigger custom events
    triggerEvent(event, ...args) {
        if (this.eventListeners[event]) {
            this.eventListeners[event](...args);
        }
    }

    // Internal method to execute the request with retries and timeout handling
    async executeRequest(url, method, requestData, headers) {
        for (let attempt = 0; attempt < this.maxRetries; attempt++) {
            try {
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), this.timeout);

                const response = await fetch(url, {
                    method: method,
                    body: JSON.stringify(requestData),
                    headers: headers,
                    signal: controller.signal
                });

                clearTimeout(timeoutId);

                if (!response.ok) {
                    throw new Error(`Request failed with status ${response.status}`);
                }

                const content = await response.text();
                const result = {
                    ok: true,
                    status: response.status,
                    content: content
                };

                this.triggerEvent('success', result);
                return result;

            } catch (error) {
                if (attempt === this.maxRetries - 1) {
                    this.triggerEvent('error', error);
                    throw error;
                }
            }
        }
    }

    // Method to handle requests
    handleRequest(url, method, data, headers = { 'Content-Type': 'application/json' }) {
        const requestData = { ...data, ...this.extraFields };
        console.log('Request Data:', requestData);

        const submitRequest = () => this.executeRequest(url, method, requestData, headers);

        let queue;
        if (this.waitForQueue) {
            // Wait for both global and local queue
            queue = yantra.globalQueue = yantra.globalQueue.then(() =>
                this.localQueue = this.localQueue.then(submitRequest)
            );
        } else {
            // Wait for local queue only
            queue = this.localQueue = this.localQueue.then(submitRequest);
        }
        return queue; // Return the promise
    }
};

yantra.FormHandler = class {
    constructor(formSelector, waitForQueue = false) {
        this.form = document.querySelector(formSelector);
        this.extraFields = {};
        this.eventListeners = {};
        this.localQueue = Promise.resolve(); // Local queue
        this.waitForQueue = waitForQueue; // Determines if the form submission should wait for both global and local queue
        this.validator = new yantra.Validator();

        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        }
    }

    // Method to add or update extra fields
    updateExtraFields(fields) {
        this.extraFields = { ...this.extraFields, ...fields };
    }

    // Method to add event listeners
    addEventListener(event, callback) {
        this.eventListeners[event] = callback;
    }

    // Method to trigger custom events
    triggerEvent(event, ...args) {
        if (this.eventListeners[event]) {
            this.eventListeners[event](...args);
        }
    }

    // Method to validate form fields
    validate(event) {
        const isValid = this.form.checkValidity();

        if (isValid) {
            this.form.querySelectorAll('[validate]').forEach(field => {
                const validateType = field.getAttribute('validate');
                const value = field.value;

                switch (validateType) {
                    case 'required':
                        if (!this.validator.validateRequired(value)) {
                            field.setCustomValidity('This field is required');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'email':
                        if (!this.validator.validateEmail(value)) {
                            field.setCustomValidity('Invalid email address');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'number':
                        if (!this.validator.validateNumber(value)) {
                            field.setCustomValidity('Invalid number');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'integer':
                        if (!this.validator.validateInteger(value)) {
                            field.setCustomValidity('Invalid integer');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'url':
                        if (!this.validator.validateURL(value)) {
                            field.setCustomValidity('Invalid URL');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'date':
                        if (!this.validator.validateDate(value)) {
                            field.setCustomValidity('Invalid date');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'phone':
                        if (!this.validator.validatePhone(value)) {
                            field.setCustomValidity('Invalid phone number');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'minLength':
                        const minLength = parseInt(field.getAttribute('minLength'), 10);
                        if (!this.validator.validateMinLength(value, minLength)) {
                            field.setCustomValidity(`Must be at least ${minLength} characters long`);
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'maxLength':
                        const maxLength = parseInt(field.getAttribute('maxLength'), 10);
                        if (!this.validator.validateMaxLength(value, maxLength)) {
                            field.setCustomValidity(`Must be at most ${maxLength} characters long`);
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'range':
                        const min = parseFloat(field.getAttribute('min'));
                        const max = parseFloat(field.getAttribute('max'));
                        if (!this.validator.validateRange(value, min, max)) {
                            field.setCustomValidity(`Must be between ${min} and ${max}`);
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'pattern':
                        const pattern = new RegExp(field.getAttribute('pattern'));
                        if (!this.validator.validatePattern(value, pattern)) {
                            field.setCustomValidity('Invalid format');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'equalTo':
                        const otherFieldSelector = field.getAttribute('equalTo');
                        const otherField = this.form.querySelector(otherFieldSelector);
                        if (otherField && value !== otherField.value) {
                            field.setCustomValidity('Does not match');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'unique':
                        // Placeholder for unique validation, depends on backend or dataset
                        break;
                    case 'alpha':
                        if (!this.validator.validateAlpha(value)) {
                            field.setCustomValidity('Must contain only alphabetic characters');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'alphanumeric':
                        if (!this.validator.validateAlphanumeric(value)) {
                            field.setCustomValidity('Must contain only alphanumeric characters');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'checkbox':
                        if (!this.validator.validateCheckbox(field)) {
                            field.setCustomValidity('Must select at least one option');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'select':
                        if (!this.validator.validateSelect(field)) {
                            field.setCustomValidity('Please select an option');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'fileType':
                        const allowedTypes = field.getAttribute('allowedTypes').split(',');
                        if (!this.validator.validateFileType(field.files, allowedTypes)) {
                            field.setCustomValidity('Invalid file type');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'fileSize':
                        const maxSize = parseInt(field.getAttribute('maxSize'), 10);
                        if (!this.validator.validateFileSize(field.files, maxSize)) {
                            field.setCustomValidity('File size exceeds the maximum limit');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                    case 'creditCard':
                        if (!this.validator.validateCreditCard(value)) {
                            field.setCustomValidity('Invalid credit card number');
                            field.reportValidity();
                            return false;
                        }
                        field.setCustomValidity('');
                        break;
                }
            });
        }

        return isValid;
    }

    // Method to handle form submission
    handleSubmit(event) {
        event.preventDefault();

        if (!this.validate(event)) {
            return;
        }

        const formData = new FormData(this.form);

        // Append extra fields to FormData
        for (const [key, value] of Object.entries(this.extraFields)) {
            formData.append(key, typeof value === 'function' ? value() : value);
        }

        // Convert FormData to URLSearchParams
        const requestData = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            requestData.append(key, value);
        }

        const submitRequest = () => {
            return fetch(this.form.action, {
                method: this.form.method,
                body: requestData,
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
                .then(async response => {
                    if (!response.ok) {
                        return {
                            ok: false,
                            status: response.status,
                            content: await response.text()
                        };
                    }
                    if (response.ok) {
                        return {
                            ok: true,
                            status: response.status,
                            content: await response.text()
                        };
                    }
                })
                .then(response => {
                    this.triggerEvent('success', response);
                    //yantra.executeScripts(response.text);
                })
                .catch(error => {
                    this.triggerEvent('error', error);
                });
        };

        if (this.waitForQueue) {
            // Wait for both global and local queue
            yantra.globalQueue = yantra.globalQueue.then(() =>
                this.localQueue = this.localQueue.then(submitRequest)
            );
        } else {
            // Wait for local queue only
            this.localQueue = this.localQueue.then(submitRequest);
        }
    }

};

yantra.JsonTable = class {
    constructor(selector, ajaxURL, columns, config = {}) {
        this.selector = selector;
        this.ajaxURL = ajaxURL;
        this.columns = columns;

        this.page = config.page || 1;
        this.perPage = config.perPage || 10;
        this.totalPages = 0;
        this.totalRecords = 0;
        this.records = [];
        this.debounceTimer = null;

        this.init();
    }

    // Initialize the table and pagination on constructor call
    init() {
        this.refresh();
        this.attachColumnSortHandlers();
    }

    // Call ajaxURL using AjaxHandler with post method to get the current page records
    load() {
        let requestData = {
            start   : (this.page - 1) * this.perPage, // Calculate the starting index
            length  : this.perPage,
            order   : {},
            search  : {}
        };

        this.columns.forEach(column => {
            if (typeof column.val === "function")
                requestData.search[column.name] = column.val();
            if (typeof column.order !== "undefined" && (column.order === "ASC" || column.order === "DESC"))
                requestData.order[column.name] = column.order;
        });

        const ajaxHandler = new yantra.AjaxHandler(true);

        return ajaxHandler.handleRequest(this.ajaxURL, 'POST', requestData)
            .then(response => {
                let resContent = JSON.parse(response.content);
                this.records = resContent.records;
                this.totalRecords = resContent.totalRecords;
                this.totalPages = Math.ceil(this.totalRecords / this.perPage);
            });
    }

    // Set page to page+1, load page records, and return loaded records
    next() {
        if (this.page < this.totalPages) {
            this.page++;
            return this.refresh();
        }
    }

    // Set page to page-1, load page records, and return loaded records
    previous() {
        if (this.page > 1) {
            this.page--;
            return this.refresh();
        }
    }

    // Set page to last page, then load the page and return loaded records
    last() {
        this.page = this.totalPages;
        return this.refresh();
    }

    // Set page to first page, then load the page and return loaded records
    first() {
        this.page = 1;
        return this.refresh();
    }

    // Sort column and refresh the table
    sortColumn(columnName) {
        const column = this.columns.find(col => col.name === columnName);
        if (column) {
            column.order = column.order === 'ASC' ? 'DESC' : 'ASC';
            this.refresh();
        }
    }

    // Refresh the table to the current page
    refresh() {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => {
            this.load().then(() => {
                this.renderTable();
                this.renderPagination();
            });
        }, 600);
    }

    // Generate pagination HTML and return
    // Generate pagination HTML
    // Render pagination controls
    renderPagination() {
        const paginationContainer = document.querySelector(`${this.selector} .pagination-container`);
        if (!paginationContainer) return;

        paginationContainer.innerHTML = ''; // Clear existing pagination

        const paginationList = document.createElement('ul');
        paginationList.className = 'pagination';

        // Previous button
        const prevButton = document.createElement('li');
        prevButton.className = `page-item ${this.page === 1 ? 'disabled' : ''}`;
        prevButton.innerHTML = `<a class="page-link" href="#">Previous</a>`;
        prevButton.addEventListener('click', (e) => {
            e.preventDefault();
            this.previous();
        });
        paginationList.appendChild(prevButton);

        // Page numbers
        for (let i = 1; i <= this.totalPages; i++) {
            const pageButton = document.createElement('li');
            pageButton.className = `page-item ${this.page === i ? 'active' : ''}`;
            pageButton.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.page = i;
                this.refresh();
            });
            paginationList.appendChild(pageButton);
        }

        // Next button
        const nextButton = document.createElement('li');
        nextButton.className = `page-item ${this.page === this.totalPages ? 'disabled' : ''}`;
        nextButton.innerHTML = `<a class="page-link" href="#">Next</a>`;
        nextButton.addEventListener('click', (e) => {
            e.preventDefault();
            this.next();
        });
        paginationList.appendChild(nextButton);

        paginationContainer.appendChild(paginationList);
    }

    // Render the table with the current page's records
    renderTable() {
        const tableContainer = document.querySelector(this.selector + ' .table-container');
        if (!tableContainer) return;

        // Clear existing table content
        tableContainer.innerHTML = '';

        // Create table elements
        const table = document.createElement('table');
        table.className = 'table';

        // Create table header
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        this.columns.forEach(column => {
            const th = document.createElement('th');
            th.textContent = column.title || column.name;
            if (column.sortable) {
                th.style.cursor = 'pointer';
                th.addEventListener('click', () => this.sortColumn(column.name));
            }
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);
        table.appendChild(thead);

        // Create table body
        const tbody = document.createElement('tbody');
        this.records.forEach(record => {
            const row = document.createElement('tr');
            this.columns.forEach(column => {
                const td = document.createElement('td');
                td.innerHTML = column.render ? column.render(record[column.name],record) : record[column.name];
                row.appendChild(td);
            });
            tbody.appendChild(row);
        });
        table.appendChild(tbody);

        // Append table to container
        tableContainer.appendChild(table);
    }
    // Attach event handlers for sorting columns
    attachColumnSortHandlers() {
        this.columns.forEach((column, index) => {
            if (column.sortable) {
                const th = document.querySelector(`${this.selector} .table-container th:nth-child(${index + 1})`);
                if (th) {
                    th.addEventListener('click', () => {
                        this.toggleSortDirection(index);
                        this.refresh();
                    });
                }
            }
        });
    }

    // Toggle the sort direction of a column
    toggleSortDirection(index) {
        this.columns[index].order = (this.columns[index].order !== 'ASC' ? 'DESC' : 'ASC');
    }
};

yantra.Component = class {
    constructor(props) {
        this.props = props || {};
    }

    init() {
        console.log('BasicComponent initialized with props:', this.props);
    }

    render() {
        return `<div>Basic Component with props: ${JSON.stringify(this.props)}</div>`;
    }
}

yantra.Service = class {
    constructor(URL, method, headers) {
        this.URL = window.app.url(URL);
        this.method = method || 'POST';
        this.headers = headers || { 'Content-Type': 'application/json' };
    }

    async run(data = {}, waitForQueue=true) {
        try {
            const ajaxHandler = new yantra.AjaxHandler(waitForQueue);
            return await ajaxHandler.handleRequest(this.URL,this.method,data,this.headers);
        } catch (error) {
            console.error("Error in API request:", error);
            throw error;
        }
    }
}

yantra.APIService = class {
    constructor(URL, method, headers, authConfig) {
        this.URL = window.app.url(URL);
        this.method = method || 'POST';
        this.headers = headers || { 'Content-Type': 'application/json' };
        this.authConfig = authConfig || {};
    }

    setAuthHeaders() {
        switch (this.authConfig.type) {
            case 'Basic':
                this.headers['Authorization'] = `Basic ${btoa(`${this.authConfig.username}:${this.authConfig.password}`)}`;
                break;
            case 'Bearer':
                this.headers['Authorization'] = `Bearer ${this.authConfig.token}`;
                break;
            case 'APIKey':
                this.headers[this.authConfig.keyName] = this.authConfig.apiKey;
                break;
            case 'OAuth':
                this.headers['Authorization'] = `OAuth ${this.authConfig.accessToken}`;
                break;
            case 'Custom':
                Object.keys(this.authConfig.customHeaders).forEach(key => {
                    this.headers[key] = this.authConfig.customHeaders[key];
                });
                break;
            default:
                console.warn('No valid authentication type provided.');
        }
    }

    async call(data = {}, waitForQueue = true) {
        try {
            this.setAuthHeaders();
            const ajaxHandler = new yantra.AjaxHandler(waitForQueue);
            return await ajaxHandler.handleRequest(this.URL, this.method, data, this.headers);
        } catch (error) {
            console.error("Error in API request:", error);
            throw error;
        }
    }
}

yantra.App = class {
    constructor(url, config = {
        componentsURL: "js/components.js",
        enableLogging: true,
        apiTimeout: 5000,
        itemsPerPage: 10,
        debugMode: true,
        apiURL: '/api',
        maxRetries: 3,
        logLevel: 'info',
        theme: 'default',
        locale: 'en',
        dateFormat: 'MM/DD/YYYY',
        currency: 'USD',
        timezone: 'UTC'
    }) {
        this.URL = url;
        this.apiURL = config.apiURL;
        this.components = config.components || {};
        this.services = config.services || {};
        this.theme = config.theme;
        this.locale = config.locale;
        this.itemsPerPage = config.itemsPerPage;
        this.dateFormat = config.dateFormat;
        this.currency = config.currency;
        this.timezone = config.timezone;
        this.logLevel = config.logLevel;
        this.enableLogging = config.enableLogging;
        this.apiTimeout = config.apiTimeout;
        this.debugMode = config.debugMode;
        this.maxRetries = config.maxRetries;
        this.componentsURL = config.componentsURL;

        if (this.enableLogging && this.debugMode) {
            console.log("yantra.App initialized with config:", config);
        }
    }

    log(message, level = 'info') {
        if (this.enableLogging && (level === this.logLevel || this.debugMode)) {
            console[level](message);
        }
    }

    registerComponent(name, ComponentClass) {
        this.components[name] = ComponentClass;
        this.log(`Component "${name}" registered.`, 'info');
    }

    getComponent(name) {
        return this.components[name];
    }

    initializeComponents() {
        const elements = document.querySelectorAll('[data-component]');
        elements.forEach(element => {
            const componentName = element.getAttribute('data-component');
            const props = JSON.parse(element.getAttribute('data-props') || '{}');

            const ComponentClass = this.getComponent(componentName);
            if (ComponentClass) {
                const componentInstance = new ComponentClass(props);
                componentInstance.init();
                element.innerHTML = componentInstance.render();
                this.log(`Component "${componentName}" initialized.`, 'info');
            } else {
                console.warn(`Component "${componentName}" is not registered.`);
            }
        });
    }

    async loadComponents() {
        try {
            const response = await fetch(this.url(this.componentsURL));
            if (!response.ok) {
                throw new Error('Failed to load components.js');
            }
            const scriptContent = await response.text();
            eval(scriptContent);
            this.log("Components loaded successfully.", 'info');
        } catch (error) {
            this.log('Error loading components: ' + error.message, 'error');
        }
    }

    url(part) {
        return `${this.URL}/${part}`;
    }

    async apiRequest(endpoint, method = 'GET', data = {}, headers = {}, waitForQueue = true) {
        try {
            const ajaxHandler = new yantra.AjaxHandler(waitForQueue, this.maxRetries, this.apiTimeout);
            return await ajaxHandler.handleRequest(this.apiURL + endpoint, method, data, headers);
        } catch (error) {
            this.log(`API request error: ${error.message}`, 'error');
            throw error;
        }
    }
}

window.yantra = yantra;

window.app = new yantra.App("http://localhost/yantra");

document.addEventListener('DOMContentLoaded', () => {
        window.app.loadComponents().then(response => {
            console.log(response);
            window.app.initializeComponents();
        } ).catch(reason => {
            console.log(reason);
        }) ;
});

/*
document.addEventListener('DOMContentLoaded', () => {
    const tabId = localStorage.getItem('tab_id') || generateUniqueId();
    localStorage.setItem('tab_id', tabId);

    fetch('http://localhost/yantra/admin/api/check-session', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Tab-Id': tabId
        }
    }).then(response => {
        if (response.status !== 200) {
            alert('Session conflict. Please reload the page.');
        }
    });

    function generateUniqueId() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0,
                v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
});
*/
