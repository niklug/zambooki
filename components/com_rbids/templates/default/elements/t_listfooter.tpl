<div class="pagination">
    {$pagination->getPagesLinks()}
    <p class="counter">
        {$pagination->getPagesCounter()}&nbsp;
        <span class="form-limit">
            {"COM_RBIDS_DISPLAY"|translate} {$pagination->getLimitBox()}
        </span>     
    </p>
</div>
