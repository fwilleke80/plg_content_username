<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.username
 * @version     1.1.4
 * @author      Frank Willeke
 * @license     GNU/GPL
 */

// No direct access to this file
defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;
use Joomla\CMS\Log\Log;

class PlgContentUsername extends CMSPlugin
{
    /**
     * Method to replace {{username}} and {{username_full}} in article content
     *
     * @param   string   $context    The context of the content being passed to the plugin.
     * @param   object   &$article   The article object. Note $article->text is also available.
     * @param   mixed    &$params    Additional parameters.
     * @param   integer  $page       Optional page number. Unused. Defaults to zero.
     *
     * @return  void
     */
    public function onContentPrepare($context, &$article, &$params, $page = 0)
    {
        // Get the current user
        $user = Factory::getUser();

        Log::add('Triggered onContentPrepare(), context: ' . $context, Log::DEBUG, 'plg_content_username');

        // Replace {{username}} and {{username_full}} in article content
        if (!empty($article->text))
        {
            $this->replacePlaceholders($article->text, $user);
        }

        // Replace {{username}} and {{username_full}} in article title
        if (!empty($article->title))
        {
            $this->replacePlaceholders($article->title, $user);
        }
    }

    /**
     * Helper method to replace placeholders in the given text.
     *
     * @param   string  &$text  The text (content or title) to modify.
     * @param   object  $user   The user object.
     *
     * @return  void
     */
    private function replacePlaceholders(&$text, $user)
    {
        // Replace {{username}} if present
        if (strpos($text, '{{username}}') !== false)
        {
            $username = $user->guest ? 'Guest' : $user->username;
            Log::add('Replaced "{{username}}" with "' . $username . '".', Log::DEBUG, 'plg_content_username');
            $text = str_replace('{{username}}', $username, $text);
        }

        // Replace {{username_full}} if present
        if (strpos($text, '{{username_full}}') !== false)
        {
            $usernameFull = $user->guest ? 'Guest' : $user->name;
            Log::add('Replaced "{{username_full}}" with "' . $usernameFull . '".', Log::DEBUG, 'plg_content_username');
            $text = str_replace('{{username_full}}', $usernameFull, $text);
        }
    }
}