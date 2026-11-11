<% if $ElementalArea.Elements.Count %>
    $ElementalArea
<% else %>
<div class="container page-text py-5 py-lg-6">
    $Breadcrumbs
    <div class="container-narrow px-0 ms-0">
        <h1 class="page-text__title">$Title</h1>
        <div class="typography">$Content</div>
        $Form
    </div>
</div>
<% end_if %>
