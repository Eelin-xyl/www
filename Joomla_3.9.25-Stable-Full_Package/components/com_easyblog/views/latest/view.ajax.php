<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(JPATH_COMPONENT . '/views/views.php');

class EasyBlogViewLatest extends EasyBlogView
{
	/**
	 * Displays the frontpage blog listings on the site.
	 *
	 * @since	5.2.0
	 * @access	public
	 */
	public function loadmore($tmpl = null)
	{
		// Get the current active menu's properties.
		$params = $this->theme->params;
		$catInclusion = '';

		if ($params) {

			// Get a list of category inclusions
			$catInclusion	= EB::getCategoryInclusion($params->get('inclusion'));

			if ($params->get('includesubcategories', 0) && !empty($catInclusion)) {

				$tmpInclusion = array();

				foreach ($catInclusion as $includeCatId) {

					// Retrieve nested categories
					$category = new stdClass();
					$category->id = $includeCatId;
					$category->childs = null;

					EB::buildNestedCategories($category->id, $category);

					$linkage = '';
					EB::accessNestedCategories($category, $linkage, '0', '', 'link', ', ');

					$catIds = array();
					$catIds[] = $category->id;
					EB::accessNestedCategoriesId($category, $catIds);

					$tmpInclusion = array_merge($tmpInclusion, $catIds);
				}

				$catInclusion = $tmpInclusion;
			}
		}

		// Sorting for the posts
		$sort = $this->input->get('sort', $this->config->get('layout_postorder'), 'cmd');
		$model = EB::model('Blog');

		$tobeCached = array();

		// Retrieve a list of featured blog posts on the site.
		$featured = $model->getFeaturedBlog($catInclusion);
		$excludeIds = array();

		// Test if user also wants the featured items to be appearing in the blog listings on the front page.
		// Otherwise, we'll need to exclude the featured id's from appearing on the front page.
		if (!$this->theme->params->get('post_include_featured', true)) {
			foreach ($featured as $item) {
				$excludeIds[] = $item->id;
			}
		}

		if ($featured) {
			$tobeCached = array_merge($tobeCached, $featured);
		}

		$excludedCategories = array();
		if ($params->get('exclusion_categories', false)) {
			$excludedCategories = $params->get('exclusion_categories');

		} else {
			// upgrades compatibility
			$tmpExcludeCategories = $this->config->get('layout_exclude_categories', null);
			if ($tmpExcludeCategories) {
				$excludedCategories	= explode( ',' , $tmpExcludeCategories );
			}
		}

		// Determines if we should explicitly include authors
		$includeAuthors = array();

		if ($params->get('inclusion_authors', false)) {
			$includeAuthors = $params->get('inclusion_authors');
		}

		// Determines if we should explicitly exclude authors
		$excludeAuthors = array();

		if ($params->get('exclusion_authors', false)) {
			$excludeAuthors = $params->get('exclusion_authors');
		}

		// Determines if we should explicitly include tags
		$includeTags = array();

		if ($params->get('inclusion_tags', false)) {
			$includeTags = $params->get('inclusion_tags');
		}

		// Check if this is filter by custom field
		$filter = $this->input->get('filter', false);
		$fields = array();
		$options = array();

		// Check if this user has saved filter search before
		$filterSaved = EB::model('fields')->getSavedFilter();

		if ($filter == 'field') {
			$filterVars = $this->input->input->getArray();
			$filterMode = $this->input->get('filtermode', 'include');

			foreach ($filterVars as $key => $value) {

				if (strpos($key, 'field') !== false) {
					$fieldId = explode('-', $key);
					$fieldId = $fieldId[1];

					$fields[$fieldId] = $filterVars[$key];

				}
			}
			// If there is a category inclusion from the field filter,
			// We override the existing cat inclusion
			$inclusion = $this->input->get('inclusion', false);

			if ($inclusion) {
				$catInclusion = $inclusion;
			}

			$options['fieldsFilterRule'] = $filterMode;
			$options['fieldsFilter'] = $fields;
		} else if ($filterSaved) {
			$params = json_decode($filterSaved->params);

			foreach ($params as $filter) {
				if (strpos($filter->name, 'field') !== false) {
					$fieldId = explode('-', $filter->name);
					$fieldId = $fieldId[1];

					$fields[$fieldId][] = $filter->value;
				}

				if ($filter->name == 'inclusion') {
					$catInclusion = $filter->value;
				}

				if ($filter->name == 'filtermode') {
					$options['fieldsFilterRule'] = $filter->value;
				}
			}

			$options['fieldsFilter'] = $fields;
		}

		$limitstart = $this->input->get('limitstart', 0, 'int');
		$isGrid = $this->input->get('isGrid', false, 'default');
		$originalLimit = EB::getViewLimit();

		$max = $originalLimit + 1;

		$options['paginationType'] = 'loadmore';

		$frontpage = true;
		$pinFeatured = $params->get('post_pin_featured', false);

		// If is grid, we need to follow the way how grid gets the blog posts
		if ($isGrid == 'true') {
			$sort = '';
			$frontpage = false;
			$excludedCategories= '';
			$pinFeatured = false;
			$includeTags = array();

			$excludeIds = array();

			if ($params->get('enable_showcase', 1)) {
				// need to exclude the post id that is in the showcase
				$excludeIds = $this->input->get('excludeIds', array(), 'array');
			}

			// Retrieve the post categories
			$catInclusion = $params->get('grid_post_category', array());

			// Format postCategories if user selected all category.
			if ($catInclusion) {
				$catInclusion = array_diff($catInclusion, array('all'));
			}
		}

		// Fetch the blog entries.
		$data = $model->getBlogsBy('', '', $sort, 0, EBLOG_FILTER_PUBLISHED, null, $frontpage, $excludeIds, false, false, true, $excludedCategories, $catInclusion, null, 'listlength', $pinFeatured, $includeAuthors, $excludeAuthors, false, $includeTags, $options);

		$showLoadMore = false;

		if ($data) {

			if (count($data) == $max) {
				$showLoadMore = true;

				// Take out the last post
				array_pop($data);
			}

			$tobeCached = array_merge($tobeCached, $data);
		}

		// Format the blog posts
		$options = array(
					'cacheComment' => false,
					'cacheCommentCount' => false,
					'cacheRatings' => false,
					'cacheTags' => false,
					'cacheAuthors' => false,
					'loadAuthor' => false
				);

		$themes = EB::themes();

		if ($themes->getParam('post_comment_counter', 0)) {
			$options['cacheCommentCount'] = true;
		}

		if ($themes->getParam('post_comment_preview', 0)) {
			$options['cacheComment'] = true;
		}

		if ($themes->getParam('post_tags', 0)) {
			$options['cacheTags'] = true;
		}

		if ($themes->getParam('post_ratings', 0)) {
			$options['cacheRatings'] = true;
		}

		if ($themes->getParam('post_author', 0) || $themes->getParam('post_author_avatar', 0)) {
			$options['cacheAuthors'] = true;
			$options['loadAuthor'] = true;
		}

		// we will cache it here.
		if ($tobeCached) {
			EB::cache()->insert($tobeCached, $options);
		}

		$view = $isGrid == 'true' ? 'grid' : 'latest';

		// Get the pagination
		$pagination	= $model->getPagination();
		$currentPageLink = $pagination->getCurrentPageLink($view, true);
		$previousPageLink = $pagination->getPreviousPageLink($view, true);

		if ($featured) {
			// Format the featured items without caching
			$featured = EB::formatter('featured', $featured, false, $options);
		}

		$options['viaAjax'] = true;

		// Perform blog formatting without caching
		$posts = EB::formatter('list', $data, false, $options);

		// Check if the listing page have contain any pinterest block
		$hasPinterestEmbedBlock = EB::hasPinterestEmbedBlock($posts);

		$themes->set('posts', $posts);
		$themes->set('return', $currentPageLink);
		$themes->set('currentPageLink', $currentPageLink);
		$themes->set('previousPageLink', $previousPageLink);
		$themes->set('autoload', true);
		$themes->set('hasPinterestEmbedBlock', $hasPinterestEmbedBlock);

		$templateStyle = $params->get('layout_style', 'default');
		$namespace = 'site/blogs/latest/posts';

		if ($templateStyle == 'card') {
			$namespace = 'site/blogs/latest/card/posts';
		}

		// Determine if this is a Gird Layout
		if ($isGrid == 'true') {
			// Determine the total grid for the list article.
			$gridLayout = $params->get('grid_layout', '4');
			$gridTruncation = $params->Get('grid_content_limit', 350) > 0 ? true : false;

			$themes->set('gridLayout', $gridLayout);
			$themes->set('gridTruncation', $gridTruncation);

			$namespace = 'site/blogs/grid/posts';
		}

		$output = $themes->output($namespace);

		if (!$showLoadMore) {
			$limitstart = '';
		} else {
			$limitstart = $limitstart + $originalLimit;
		}

		$data = new stdClass();
		$data->contents = $output;
		$data->limitstart = $limitstart;
		$data->hasPinterestEmbedBlock = $hasPinterestEmbedBlock;

		$data = json_encode($data);

		return $this->ajax->resolve($data);
	}
}
