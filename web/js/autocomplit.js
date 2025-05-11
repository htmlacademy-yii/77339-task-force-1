window.onload = function () {
    let config = {
        selector: "#addtaskform-location",
        searchEngine: 'loose',
        placeHolder: "Search for Location...",
        data: {
            src:  async (query) => {
                try {
                    // Fetch Data from external Source
                    const source = await fetch('add-task/get-location?' + new URLSearchParams({
                        query: `${query}`,
                    }), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest', // Устанавливаем заголовок для AJAX-запроса
                            'Content-Type': 'application/json', // Устанавливаем тип контента как JSON
                            'X-CSRF-Token': yii.getCsrfToken(),
                        },
                    });
                    // Data should be an array of `Objects` or `Strings`
                    const data = await source.json();
                    return data;
                } catch (error) {
                    console.log('error', error);
                    return error;
                }
            },
            // Data source 'Object' key to be searched
            keys: ['address']
        },
        resultsList: {
            element: (list, data) => {
                if (!data.results.length) {
                    // Create "No Results" message element
                    const message = document.createElement("div");
                    // Add class to the created element
                    message.setAttribute("class", "no_result");
                    // Add message text content
                    message.innerHTML = `<span>Found No Results for "${data.query}"</span>`;
                    // Append message element to the results list
                    list.prepend(message);
                }
            },
            noResults: true,
        },
        resultItem: {
            highlight: true,
        },
        events: {
            input: {
                focus: () => {
                    if (autoCompleteJS.input.value.length) autoCompleteJS.start();
                },
                response: (event) => {
                    autoCompleteJS.input.value = autoCompleteJS.input.value.replace('дом', '');
                },
                selection: (event) => {
                    const selection = event.detail.selection.value;
                    autoCompleteJS.input.value = selection.address;
                    latitude.value = selection.latitude;
                    longitude.value = selection.longitude;
                }
            }
        }
    };

    const autoCompleteJS = new autoComplete(config);
    const latitude = document.getElementById('addtaskform-latitude');
    const longitude = document.getElementById('addtaskform-longitude');
}
