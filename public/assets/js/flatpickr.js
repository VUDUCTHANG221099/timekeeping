const flatpickrDate = () => {
    flatpickr("#flatpickr_start", {
        // locale: "ja",
        maxDate: "today",
        dateFormat: "Y/m/d",
    });
    flatpickr("#flatpickr_end", {
        // locale: "ja",
        minDate: "today",
        dateFormat: "Y/m/d",
    });
    flatpickr("#flatpickr", {
        // locale: "ja",
        maxDate: "today",
        dateFormat: "Y/m/d",
    });
    
};
flatpickrDate();
