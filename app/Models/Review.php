<?php
namespace App\Models;

class Review extends AbstractModel
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'reviews';

	public $timestamps = true;

	/**
	 * Primary key
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';

	protected $rules = [
			'title' => 'required|string|max:255',
			'customer' => 'string',
			'thumbnail' => 'string|max:255',
			'content' => 'string|required|between:10,5000',
			'status' => 'integer|between:0,1',
	];

	protected $editableFields = [
			'title',
			'customer',
			'thumbnail',
			'content',
			'status',
			'slug',
	];
	
	public function createItem($data)
	{
		$dataPost = ['status' => 1];
		if (isset($data['title'])) {
				$dataPost['title'] = $data['title'];
		}

		if (!isset($data['status'])) {
			$data['status'] = 1;
		}

		$resultCreateItem = $this->updateItem(0, $dataPost);

		/*No error*/
		if (!$resultCreateItem['error']) {
				$post_id = $resultCreateItem['object']->id;
				$resultUpdateItemContent = $this->updateItemContent($post_id, $data);
				if ($resultUpdateItemContent['error']) {
						$this->deleteItem($resultCreateItem['object']->id);
				}
				return $resultUpdateItemContent;
		}
		return $resultCreateItem;
	}

	public function updateItem($id, $data, $justUpdateSomeFields = true)
	{
		$data['id'] = $id;
		$result = $this->fastEdit($data, true, $justUpdateSomeFields);
		return $result;
	}

	public function updateItemContent($id, $data)
	{
		$result = [
				'error' => true,
				'response_code' => 500,
				'message' => 'Xảy ra một số lỗi!',
		];
		$post = static::find($id);
		if (!$post) {
				$result['message'] = 'Các bài viết mà bạn đã cố gắng chỉnh sửa không tìm thấy.';
				$result['response_code'] = 404;
				return $result;
		}
		if (isset($data['slug'])) {
			$data['slug'] = str_slug($data['title']);
		}
		/*Update post content*/
		$postContent = $post;
		if (!$postContent) {
			$postContent->save();
		}
		$data['id'] = $id;
		return $postContent->fastEdit($data, false, true);
	}

	public static function getById($id, $options = [], $select = [])
	{
		$options = (array) $options;
		$defaultArgs = [
			'status' => 1,
		];
		$args = array_merge($defaultArgs, $options);
		$select = (array) $select;
		if (!$select) {
			$select = [
				'reviews.title',
				'reviews.slug',
				'reviews.content',
				'reviews.thumbnail',
				'reviews.status',
				'reviews.customer',
				'reviews.id'
			];
		}

		return static::where('id', '=', $id)
				->where(function ($q) use ($args) {
						if ($args['status'] != null) {
								$q->where('status', '=', $args['status']);
						}
				})
				->select($select)
				->first();
	}
	
	public static function getBySlug($slug, $options = [], $select = [])
	{
		$options = (array) $options;
		$defaultArgs = [
			'status' => 1,
		];
		$args = array_merge($defaultArgs, $options);

		$select = (array) $select;
		if (!$select) {
			$select = [
				'reviews.title',
				'reviews.slug',
				'reviews.content',
				'reviews.thumbnail',
				'reviews.status',
				'reviews.customer',
				'reviews.id'
			];
		}

		return static::where('slug', '=', $slug)
				->where(function ($q) use ($args) {
					if ($args['status'] != null) {
						$q->where('reviews.status', '=', $args['status']);
					}
				})
				->select($select)
				->first();
	}
	
	public static function getWithContent($fields = [], $select = [], $order = null, $multiple = false, $perPage = 0)
	{
		$fields = (array) $fields;
		$select = (array) $select;

		if (!$select) {
			$select = [
				'reviews.title',
				'reviews.slug',
				'reviews.content',
				'reviews.thumbnail',
				'reviews.status',
				'reviews.customer',
				'reviews.id'
			];
		}
		$obj = static::select($select);
		if ($fields && is_array($fields)) {
			foreach ($fields as $key => $row) {
				$obj = $obj->where(function ($q) use ($key, $row) {
					switch ($row['compare']) {
						case 'LIKE':{
							$q->where($key, $row['compare'], '%' . $row['value'] . '%');
						}break;
						case 'IN':{
							$q->whereIn($key, (array) $row['value']);
						}break;
						case 'NOT_IN':{
							$q->whereNotIn($key, (array) $row['value']);
						}break;
						default:{
							$q->where($key, $row['compare'], $row['value']);
						}break;
					}
				});
			}
		}
		if ($order && is_array($order)) {
			foreach ($order as $key => $value) {
				$obj = $obj->orderBy($key, $value);
			}
		}
		if ($order == 'random') {
			$obj = $obj->orderBy(\DB::raw('RAND()'));
		}
		$obj = $obj->groupBy('reviews.id');

		if ($multiple) {
			if ($perPage > 0) {
				return $obj->paginate($perPage);
			}
			return $obj->get();
		}
		return $obj->first();
	}

	public static function deleteItem($id)
	{
		$result = [
			'error' => true,
			'response_code' => 500,
			'message' => 'Xảy ra một số lỗi!',
		];
		$object = static::find($id);

		if (!$object) {
			$result['message'] = 'Các bài viết mà bạn đã cố gắng chỉnh sửa không tìm thấy.';
			return $result;
		}

		if ($object->delete()) {
			$result['error'] = false;
			$result['response_code'] = 200;
			$result['message'] = ['Xóa bài hoàn thành!'];
		}

		return $result;
	}
}
