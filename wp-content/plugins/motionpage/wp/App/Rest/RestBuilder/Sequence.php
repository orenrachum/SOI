<?php

/**
 * Motion.page
 *
 * @package   motionpage
 * @author    HypeWolf OÜ <hello@hypewolf.com>
 * @copyright 2022 HypeWolf OÜ
 * @license   EULA + GPLv2
 * @link      https://motion.page
 */

declare(strict_types=1);

namespace motionpage\App\Rest\RestBuilder;

use motionpage\Common\Utils\Errors;

/**
 * Get images from sequence folder
 * Delete folder from sequence folder
 * @since 2.1.0
 */
class Sequence extends AllPoints {
	public function sequenceGet(): \WP_REST_Response {
		//$method = $request->get_method();
		//Errors::writeLog($method);

		// Set custom error handler for API errors
		set_error_handler([Errors::class, 'handleApiErrors']);

		$sequence_folder = $this->plugin->sequenceFolder();
		$files = [];
		$exclusions = ['.', '..', 'index.php'];
		$sequenceFolder = '/' . $sequence_folder . '/';
		$path = \wp_get_upload_dir()['basedir'] . $sequenceFolder;

		// Try to open the directory and handle errors
		try {
			$dir = opendir($path);
		} catch (\Throwable $e) {
			return new \WP_REST_Response(['error' => $e->getMessage()]);
		}

		// Function to get a random image from a folder
		$getRandomImage = function ($folder) use (&$path, &$sequenceFolder) {
			$files = @scandir($path . $folder);

			// If there are files, filter the valid image files
			if ($files !== false && is_array($files)) {
				$files = array_values(
					array_filter(
						$files,
						fn($file): bool => in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'webp'])
					)
				);

				$dir_date = filemtime($path . $folder);
				$fileIndex = array_rand($files, 1);
				$baseUrl = \wp_get_upload_dir()['baseurl'] . $sequenceFolder . $folder . '/';
				$url = $baseUrl . $files[$fileIndex];
				$ext = pathinfo($url, PATHINFO_EXTENSION);

				$first_image = $files[0];
				$last_image = $files[count($files) - 1];
				$firstFileName = basename($first_image, '.' . $ext);
				$lastFileName = basename($last_image, '.' . $ext);
				$numPadding = $firstFileName !== $lastFileName ? strlen($firstFileName) : 0;

				$folderName = basename($folder);
				$numFiles = count($files);

				return (object) [
					'url' => $url,
					'numFiles' => $numFiles,
					'baseUrl' => $baseUrl,
					'ext' => $ext,
					'dirDate' => $dir_date,
					'folderName' => $folderName,
					'numPadding' => $numPadding
				];
			}

			return '';
		};

		// Loop through the directory and process each folder
		while (($file = readdir($dir)) !== false) {
			// Skip hidden and excluded files
			if ($file[0] === '.' || in_array($file, $exclusions, true)) {
				continue;
			}

			// Process each directory
			if (is_dir($path . $file)) {
				$randomImage = $getRandomImage($file);
				if ($randomImage) {
					$files[] = $randomImage;
				}
			}
		}

		// Close the directory
		@closedir($dir);

		// Sort files by directory date
		usort($files, function ($a, $b): int {
			if ($a->dirDate == $b->dirDate) {
				return 0;
			}

			return $a->dirDate < $b->dirDate ? -1 : 1;
		});

		return new \WP_REST_Response(['images' => $files]);
	}

	public function sequencePost(\WP_REST_Request $request): \WP_REST_Response {
		$type = filter_var($request['type'], FILTER_SANITIZE_STRING);
		$folder_to_remove = filter_var($request['folder'], FILTER_SANITIZE_STRING);

		if (!$folder_to_remove) {
			http_response_code(400);
			exit();
		}

		switch ($type) {
			case 'DELETE':
				// TODO : Remove also from wp.media folder - we should upload one file with specific name?
				motionpage()->removeFolderFiles($folder_to_remove);
				return new \WP_REST_Response(['folder' => $folder_to_remove]);

			default:
				http_response_code(501);
				exit();
		}
	}
}
