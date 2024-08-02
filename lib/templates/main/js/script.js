/**
 * @class OneCFitnessServiceBooking
 */
class OneCFitnessServiceBooking
{
    pathToAjax = './lib/api/ajax.php';
    requestParams = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8',
        },
        body: '',
    }
    loadScheduleAction = 'loadSchedule';
    sendBookingAction = 'sendBooking';

    dateConfirmBtn = null;
    maxCountValueInput = null;
    serviceSelector = null;
    submitBtn = null;

    serviceSlotTypes = ['evening' , 'morning'];
    maxPlayersCount = 1;
    services = {};

    constructor()
    {
        this.dateConfirmBtn = document.getElementById('calendar-confirm-btn');
        if (!this.dateConfirmBtn)
        {
            this.errorMessage('Date confirm btn not found');
            return false;
        }

        this.maxCountValueInput = document.getElementById('players-count');
        if (!this.maxCountValueInput)
        {
            this.errorMessage('Max players input not found');
        }

        this.serviceSelector = document.getElementById('service');
        if (!this.serviceSelector)
        {
            this.errorMessage('Service selector not found');
            return false;
        }

        this.submitBtn = document.getElementById('service-booking-form-submit');
        if (!this.submitBtn)
        {
            this.errorMessage('Submit button not found');
            return false;
        }
    }

    init() {
        this.initFooter();
        this.initDatepicker();
        this.initDateConfirmBtn();
        this.initServiceSelector();
        this.initMaxPLayersInput();
        this.initForm();
    }

    initDatepicker(){
        this.datepicker = new Datepicker('#calendar-input', {
            inline: true,
            weekStart: 1,
            min: new Date(),
            yearRange: 1,
            onChange: (date) => {
                if (date) {
                    this.dateConfirmBtn.removeAttribute('disabled');
                } else {
                    this.dateConfirmBtn.setAttribute('disabled', 'true');
                }
                this.setServiceSelectorToDisabledMode();
                this.disableTimeAndCountInputs();
            }
        });
    }

    initDateConfirmBtn(){
        const dateInput = document.getElementById('calendar-input');
        if (!dateInput)
        {
            this.errorMessage('Date input not found');
            return false;
        }

        this.dateConfirmBtn.addEventListener('click', async() => {
            try
            {
                if (!dateInput.value)
                {
                    this.errorMessage('Необходимо выбрать дату');
                    return false;
                }

                this.setServiceSelectorToLoadingMode();

                const formData = new FormData();
                formData.set('action', this.loadScheduleAction)
                formData.set('date', dateInput.value)
                this.requestParams.body = JSON.stringify(Object.fromEntries(formData.entries()));
                const response = await fetch(this.pathToAjax, this.requestParams);
                if (response.ok)
                {
                    const result = await response.json();
                    if(result.success)
                    {
                        if (typeof result.data?.services === 'object')
                        {
                            this.services = result.data.services;
                            this.dateConfirmBtn.setAttribute('disabled', 'true');
                            this.disableTimeAndCountInputs();

                            this.setServiceSelectorToActiveMode();
                            for (let serviceId in this.services)
                            {
                                const option = document.createElement('option');
                                option.value = serviceId;
                                option.textContent = this.services[serviceId]['title'];
                                this.serviceSelector.append(option);
                            }
                        }
                        else
                        {
                            this.errorMessage('Services is empty');
                        }
                    }
                    else if (result.error)
                    {
                        this.errorMessage(result.error);
                    }
                    else
                    {
                        this.errorMessage('Can not decode server response - unexpected structure.');
                    }
                }
                else
                {
                    this.errorMessage('Can not connect to 1c. Status code - ' + response.status);
                }
            }
            catch (e)
            {
                this.errorMessage(e);
            }
        });
    }

    initForm(){
        const form = document.getElementById('service-booking-form');
        form && form.addEventListener('submit', async(e) => {
            e.preventDefault();
            try
            {
                const formData = new FormData(e.target);
                if (!formData.get('date'))
                {
                    this.errorMessage('Необходимо выбрать дату');
                    return false;
                }

                if (!formData.get('service'))
                {
                    this.errorMessage('Необходимо выбрать услугу');
                    return false;
                }

                let timeSelected = false;
                this.serviceSlotTypes.forEach(type => {
                    if (formData.get(type))
                    {
                        timeSelected = true;
                        return true;
                    }
                });
                if (!timeSelected){
                    this.errorMessage('Необходимо выбрать время');
                    return false;
                }

                this.submitBtn.setAttribute('disabled', 'true');

                formData.set('action', this.sendBookingAction);
                this.requestParams.body = JSON.stringify(Object.fromEntries(formData.entries()));
                const response = await fetch(this.pathToAjax, this.requestParams);
                if (response.ok)
                {
                    const result = await response.json();
                    if(result.success)
                    {
                        this.showSuccessPopup();
                    }
                    else if (result.error)
                    {
                        this.errorMessage(result.error);
                    }
                    else
                    {
                        this.errorMessage('Can not decode server response - unexpected structure.');
                    }
                }
                else
                {
                    this.errorMessage('Can not connect to 1c. Status code - ' + response.status);
                }

                this.submitBtn.removeAttribute('disabled');
            }
            catch (e)
            {
                this.errorMessage(e);
                this.submitBtn.removeAttribute('disabled');
            }
            return false;
        });

    }

    initFooter(){
        const footerText = document.getElementById('currentYear');
        if (footerText){
            footerText.textContent = (new Date).getFullYear().toString();
        }
    }

    initMaxPLayersInput() {
        this.setMaxPlayersText();
        this.maxCountValueInput.addEventListener('input', (e) => {
            const value = Number(e.target.value);
            if (value < 1)
            {
                e.target.value = 1;
            }
            else if(value > this.maxPlayersCount)
            {
                e.target.value = this.maxPlayersCount;
            }
        });
    }

    setMaxPlayersText(){
        let maxCountValueText = document.getElementById('max_count_info');
        maxCountValueText && (maxCountValueText.textContent = `${this.maxPlayersCount}`);
    }

    errorMessage(error) {
        console.log(error);
        this.showErrorPopup(error);
    }

    initServiceSelector() {
        this.serviceSelector.addEventListener('change', (e) => {
            const serviceId = e.target.value;
            if (serviceId && this.services.hasOwnProperty(serviceId))
            {
                const slots = this.services[serviceId]['slots'];
                if (typeof slots === 'object')
                {
                    this.serviceSlotTypes.forEach(type => {
                        const timeInput = document.querySelector(`input#${type}`);
                        if (timeInput)
                        {
                            timeInput.checked = false;
                            if (Number(slots[type]) <= 0)
                            {
                                timeInput.setAttribute('disabled', 'true');
                            }
                            else
                            {
                                timeInput.removeAttribute('disabled');
                            }
                        }
                    });
                }

                this.maxPlayersCount = Number(this.services[serviceId]['maxGuestCount']);
                this.maxCountValueInput.value = 1;
                this.maxCountValueInput.removeAttribute('disabled');
                this.setMaxPlayersText();
            }
            else
            {
                this.disableTimeAndCountInputs();
            }
        });
    }

    getDefaultServiceOption(text) {
        const option = document.createElement('option');
        option.value = '';
        option.selected = true;
        option.textContent = text;
        return option;
    }

    disableTimeAndCountInputs() {
        this.serviceSlotTypes.forEach(type => {
            const timeInput = document.querySelector(`input#${type}`);
            if (timeInput)
            {
                timeInput.checked = false;
                timeInput.setAttribute('disabled', 'true');
            }
        });

        this.maxCountValueInput.setAttribute('disabled', 'true');
    }

    setServiceSelectorToLoadingMode() {
        this.serviceSelector.setAttribute('disabled', 'true');
        this.serviceSelector.innerHTML = '';
        this.serviceSelector.value = '';
        this.serviceSelector.append(this.getDefaultServiceOption('Идёт загрузка услуг...'));
    }

    setServiceSelectorToActiveMode() {
        this.serviceSelector.removeAttribute('disabled');
        this.serviceSelector.innerHTML = '';
        this.serviceSelector.value = '';
        this.serviceSelector.append(this.getDefaultServiceOption('Выберите услугу'));
    }

    setServiceSelectorToDisabledMode() {
        this.serviceSelector.setAttribute('disabled', true);
        this.serviceSelector.innerHTML = '';
        this.serviceSelector.value = '';
        this.serviceSelector.append(this.getDefaultServiceOption('Сначала выберите дату'));
    }

    showErrorPopup(error) {
        this.showPopup(error, true, false);
    }

    showSuccessPopup() {
        this.showPopup('Запись произведена успешно', false, true);
    }

    showPopup(text = 'text', canClose = true, withMainPageLink = false) {
        //todo bootstrap popup
    }
}