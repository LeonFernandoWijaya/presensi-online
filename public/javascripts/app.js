function showFlowBytesModal(id) {
    let modal = FlowbiteInstances.getInstance("Modal", id);
    if (modal == null) {
        modal = new Modal(document.getElementById(id), { placement: "center" });
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
