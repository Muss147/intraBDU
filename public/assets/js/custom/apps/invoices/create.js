$(document).ready(function () {
    var $form = $('#kt_invoice_form');

    function updateTotals() {
        var total = 0;
        var formatter = wNumb({ decimals: 0, thousand: "." });

        $form.find('[data-kt-element="item"]').each(function () {
            var $item = $(this);
            var qty = parseInt($item.find('[data-kt-element="quantity"]').val()) || 1;
            var price = formatter.from($item.find('[data-kt-element="price"]').text()) || 0;

            $item.find('[data-kt-element="total"]').text(formatter.to(price * qty));

            total += price * qty;
        });

        // $form.find('[data-kt-element="sub-total"]').text(formatter.to(total));devis[total]
        $form.find('[name="devis[total]"]').val(total);
        $form.find('[data-kt-element="grand-total"]').text(formatter.to(total));
    }

    function refreshEmptyTemplate() {
        const $tbody = $form.find('[data-kt-element="items"] tbody');
        const $rows = $tbody.find('[data-kt-element="item"]');

        if ($rows.length === 0) {
            const $emptyRow = $form.find('[data-kt-element="empty-template"] tr').clone();
            $tbody.append($emptyRow);
        } else {
            $form.find('[data-kt-element="empty"]').remove();
        }
    }

    function cleanSelectLabel($select) {
        const selectedText = $select.find('option:selected').text();
        const cleanedText = selectedText.replace(/^—+/, '').trim();

        // Récupérer le conteneur Select2 affiché
        const renderedId = $select.attr('aria-labelledby') || `select2-${$select.attr('id')}-container`;
        const $rendered = $('#' + renderedId);

        // Met à jour le texte et le title affiché dans Select2
        $rendered.text(cleanedText);
        $rendered.attr('title', cleanedText);
    }

    function updateBpuDetails($select) {
        const selectedOption = $select.find('option:selected');
        const prix = selectedOption.attr('data-prix');
        const unite = selectedOption.attr('data-unite');
        const hierarchyText = selectedOption.attr('data-hierarchie');

        const pricePlace = $select.closest('tr').find('[data-kt-element="price"]');
        const unityPlace = $select.closest('tr').find('[data-kt-element="unity"]');
        
        const $itemRow = $select.closest('tr');
        // Supprimer une éventuelle ligne hiérarchie précédente
        const $prev = $itemRow.prev();
        if ($prev.hasClass('bpu-hierarchy-row')) {
            $prev.remove();
        }

        // Créer une nouvelle ligne hiérarchie
        const $hierarchyRow = $(`
            <tr class="bpu-hierarchy-row">
                <td colspan="5" class="text-muted fw-semibold small pb-0">`+hierarchyText+`</td>
            </tr>
        `);
        $itemRow.before($hierarchyRow);

        // Tu peux ici afficher les infos dans le DOM, par exemple :
        $(pricePlace).text(prix);
        $(unityPlace).text(unite);
    }

    // Ajout dynamique d'un item
    $form.on('click', '[data-kt-element="add-item"]', function (e) {
        e.preventDefault();

        const $tbody = $form.find('[data-kt-element="items"] tbody');
        const prototype = $tbody.data('prototype');
        let index = $tbody.data('index') || $tbody.find('[data-kt-element="item"]').length;

        const newForm = prototype.replace(/__name__/g, index);
        $tbody.data('index', index + 1);

        const $newRow = $('<tbody>' + newForm + '</tbody>').find('[data-kt-element="item"]');
        $tbody.append($newRow);

        // Réinitialiser les Select2 sur les nouveaux champs
        $newRow.find('.form-select').select2();

        // Nettoyer les sélections immédiatement si un tiret est présent
        $newRow.find('select').each(function () {
            cleanSelectLabel($(this));
        });

        refreshEmptyTemplate();
        updateTotals();
    });

    // Suppression d'un item
    $form.on('click', '[data-kt-element="remove-item"]', function (e) {
        e.preventDefault();
        $(this).closest('[data-kt-element="item"]').prev('.bpu-hierarchy-row').remove();
        $(this).closest('[data-kt-element="item"]').remove();
        refreshEmptyTemplate();
        updateTotals();
    });

    // Mise à jour des totaux sur changement de quantité ou prix
    $form.on('change', '[data-kt-element="quantity"], [data-kt-element="price"]', function () {
        updateTotals();
    });

    // Suppression des tirets lors de la sélection (Select2)
    $form.on('select2:select', 'select', function () {
        cleanSelectLabel($(this));
        updateBpuDetails($(this));
        updateTotals();
    });

    // Nettoyer au chargement si déjà une valeur
    $('[data-kt-element="items"] select.form-select').each(function() {
        cleanSelectLabel($(this));
        updateBpuDetails($(this));
    });
    
    // Initialisation des totaux au chargement
    updateTotals();
    
});