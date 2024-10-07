function buttonPagination(selector, numberOfPage, currentPage, link) {
    $(selector).empty();
    let currentPageButton = `<li>
    <button aria-current="page"
    onclick="${link}({{page}})"
        class="flex items-center justify-center mx-1 cursor-pointer rounded-lg px-3 h-8 text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700">{{page}}</button>

    </li>`;
    // Other Page
    let otherPage = `<li>
    <a onclick="${link}({{page}})"
        class="flex items-center justify-center me-1 px-3 h-8 leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700 cursor-pointer rounded-lg">{{page}}</a>
    </li>`;
    let button = `<li>
    <button type="button" onclick="${link}({{page}})"
        class="flex items-center justify-center cursor-pointer px-3 h-8 leading-tight text-gray-500 bg-white rounded-lg hover:bg-gray-100 hover:text-gray-700">{{button-text}}</button>
    </li>`;
    let dots = `<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>`;
    let previousButton = button.replace("{{button-text}}", "<");
    if (currentPage != 1) {
        previousButton = previousButton.replace("{{page}}", currentPage - 1);
    }
    let buildedPage = previousButton;
    const TOTAL_SIDE_BUTTON = 3;
    if (currentPage > TOTAL_SIDE_BUTTON + 2) {
        buildedPage += otherPage.replaceAll("{{page}}", 1);
        buildedPage += otherPage.replaceAll("{{page}}", 2);
        buildedPage += dots;
    }

    let startingMiddlePage = currentPage - TOTAL_SIDE_BUTTON;
    if (startingMiddlePage < 1 || startingMiddlePage == 2) {
        startingMiddlePage = 1;
    }

    let endMiddlePage = currentPage + TOTAL_SIDE_BUTTON;
    if (endMiddlePage > numberOfPage || endMiddlePage == numberOfPage - 1) {
        endMiddlePage = numberOfPage;
    }

    for (let i = startingMiddlePage; i <= endMiddlePage; i++) {
        if (i === currentPage) {
            buildedPage += currentPageButton.replaceAll("{{page}}", i);
        } else {
            buildedPage += otherPage.replaceAll("{{page}}", i);
        }
    }
    if (currentPage < numberOfPage - TOTAL_SIDE_BUTTON - 1) {
        buildedPage += dots;
        buildedPage += otherPage.replaceAll("{{page}}", numberOfPage - 1);
        buildedPage += otherPage.replaceAll("{{page}}", numberOfPage);
    }

    let nextButton = button.replaceAll("{{button-text}}", ">");
    if (currentPage != numberOfPage) {
        nextButton = nextButton.replace("{{page}}", currentPage + 1);
    }
    buildedPage += nextButton;
    $(selector).append(buildedPage);
}

function showFlowBytesModal(id) {
    let modal = FlowbiteInstances.getInstance("Modal", id);
    if (modal == null) {
        modal = new Modal(document.getElementById(id), {
            placement: "center",
            backdrop: "static",
            closable: false,
        });
    }
    modal.show();
}
function hideFlowBytesModal(id) {
    let modal = FlowbiteInstances.getInstance("Modal", id);
    if (modal == null) {
        modal = new Modal(document.getElementById(id), { placement: "center" });
    }
    modal.hide();
}
