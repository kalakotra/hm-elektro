$HeaderElement

<% if $Services %>
<section class="services-list py-6 py-lg-7" aria-labelledby="services-heading">
    <div class="container">
        <header class="section-head<% if not $ListTitle %> visually-hidden<% end_if %>">
            <% if $ListEyebrow %><p class="eyebrow">$ListEyebrow</p><% end_if %>
            <h2 id="services-heading" class="section-head__title"><% if $ListTitle %>$ListTitle<% else %>$Title<% end_if %></h2>
        </header>
        <ul class="row g-4 list-unstyled mb-0 row-cols-1 row-cols-md-2 row-cols-lg-3">
            <% loop $Services %>
            <li class="col"><% include ServiceCard %></li>
            <% end_loop %>
        </ul>
    </div>
</section>
<% end_if %>

<% loop $MainElements %>
    $Me
<% end_loop %>
