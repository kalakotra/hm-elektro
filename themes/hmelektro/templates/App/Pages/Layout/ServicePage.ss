$ElementalArea

<% if $RelatedList %>
<section class="related-services py-6 py-lg-7" aria-labelledby="related-heading">
    <div class="container">
        <header class="section-head">
            <p class="eyebrow"><%t App.MoreServices 'Weitere Leistungen' %></p>
            <h2 id="related-heading" class="section-head__title"><% if $RelatedTitle %>$RelatedTitle<% else %><%t App.MoreServices 'Weitere Leistungen' %><% end_if %></h2>
        </header>
        <ul class="row g-4 list-unstyled mb-0 row-cols-1 row-cols-md-2 row-cols-lg-3">
            <% loop $RelatedList %>
            <li class="col"><% include ServiceCard %></li>
            <% end_loop %>
        </ul>
    </div>
</section>
<% end_if %>

$BottomArea
