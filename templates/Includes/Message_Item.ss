<a href="$ObjectLink" title="$ObjectTitle" class="inbox-message <% if not IsRead %>unread<% end_if %>">
    <div class="col-md-3">
        $CreatedBy.Title
    </div>
    <div class="col-md-7">$ObjectTitle - $Content.LimitCharacters(30)</div>
    <div class="col-md-2">$Created.Nice </div>
</a>