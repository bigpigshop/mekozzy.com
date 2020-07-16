<?php
//@license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
defined('_JEXEC') or die('Restricted access');
?>
<form action="<?php echo JRoute::_(JUri::getInstance()->toString()) ?>" method="post" id="login-form" class="form-inline">
    <div class="userdata">
        <div id="form-login-username" class="control-group">
            <div class="controls">
                <div class="input-prepend input-append">
                    <span class="add-on">
                        <span class="icon-user tip hasTooltip" title="" data-original-title="User Name"></span>
                        <label for="modlgn-username" class="element-invisible">User Name</label>
                    </span>
                    <input id="modlgn-username" type="text" name="username" class="input-small" tabindex="0" size="18" placeholder="User Name">
                </div>
            </div>
        </div>
        <div id="form-login-password" class="control-group">
            <div class="controls">
                <div class="input-prepend input-append">
                    <span class="add-on">
                        <span class="icon-lock tip hasTooltip" title="" data-original-title="Password">
                        </span>
                    <label for="modlgn-passwd" class="element-invisible">Password</label>
                    </span>
                    <input id="modlgn-passwd" type="password" name="password" class="input-small" tabindex="0" size="18" placeholder="Password">
                </div>
            </div>
        </div>
	    <div id="form-login-remember" class="control-group checkbox">
            <label for="modlgn-remember" class="control-label">Remember Me</label>
            <input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes">
        </div>
        <div id="form-login-submit" class="control-group">
            <div class="controls">
                <button type="submit" tabindex="0" name="Submit" class="btn btn-primary">Log in</button>
            </div>
        </div>
        <ul class="unstyled">
            <li>
                <a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
                Create an account <span class="icon-arrow-right"></span>
                </a>
            </li>
       	    <li>
                <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
                  Forgot your username?</a>
            </li>
            <li>
                <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">Forgot your password?</a>
            </li>
        </ul>
        <input type="hidden" name="option" value="com_users">
        <input type="hidden" name="task" value="user.login">
        <input type="hidden" name="return" value="aW5kZXgucGhwP29wdGlvbj1jb21fdmlydHVlbWFydCZ2aWV3PWNhcnQ=">
        <input type="hidden" name="925fd1616080e52f63e5f06328a09223" value="1">    
    </div>
</form>