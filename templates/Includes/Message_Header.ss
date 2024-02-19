<div class="inbox-message-header">
    <h4 class="inbox-message-title">$ObjectTitle</h4>
    
    <div class="inbox-message-header-img">
        <a href="">
            <% if $CreatedBy.ProfileImage %>
                <img src="$CreatedBy.ProfileImage.PaddedImage(50, 50).URL" class="img-circle img-user" />
            <% else %>
                <img src="$themedResourceURL('images/icon-user.png')" class="img-circle img-user" />
            <% end_if %>
        </a>
    </div>
    <div class="inbox-message-header-content">
        <p class="inbox-sender"><a>$CreatedBy.Title</a></p>
        <div class=""><small>$Created.Nice</small></div>
    </div>
</div>
