{
    const footerText = document.getElementById('currentYear');
    if (footerText){
        footerText.textContent = (new Date).getFullYear().toString();
    }
}

{
    let datepicker = new Datepicker('#calendar-input', {
        inline: true,
        weekStart: 1,
        min: new Date(),
        yearRange: 1,
    });
}

{
    let maxValue = 15;
    let maxCountValueInput = document.getElementById('max_count_value');
    let maxCountValueText = document.getElementById('max_count_info');
    maxCountValueText && (maxCountValueText.textContent = `${maxValue}`);
    maxCountValueInput && maxCountValueInput.addEventListener('input', (e) => {
        const value = Number(e.target.value);
        if (value < 1)
        {
            e.target.value = 1;
        }
        else if(value > maxValue)
        {
            e.target.value = maxValue;
        }
    });
}

{
    const form = document.getElementById('service-booking-form');
    form && form.addEventListener('submit', (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        for (let [key, value] of formData)
        {
            console.log(`${key} - ${value}`);
        }
        return false;
    });
}