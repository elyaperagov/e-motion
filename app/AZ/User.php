<?

namespace AZ;

class User
{
  const META = [
    'field'
  ];

  public function insert($data)
  {
    foreach ($data as $key => &$item) {
      sanitize_text_field(trim($item));
    }

    $response['status'] = 'fail';
    $response['message'] = 'Что-то пошло не так(';
    $response['error'];

    if (isset($data['recaptcha_response'])) {
      $captcha = Helpers::captchaCheck($data['recaptcha_response']);
      if (!$captcha['success']) {
        $response['message'] = 'Проверка на бота не пройдена';
        $response['captcha'] = $captcha;
        return $response;
      }
    }

    $errors = [];

    if (isset($data['ID'])) {
      unset($data['ID']);
    }

    if (!isset($data['user_email']) || empty($data['user_email']) || !is_email($data['user_email'])) {
      $errors[] = 'Невалидный email';
    } else {
      $offset = mb_strpos($data['user_email'], '@');
      $data['user_login'] = mb_substr($data['user_email'], 0, $offset);
    }
    if (!isset($data['user_login']) || empty($data['user_login'])) {
      $errors[] = 'Логин не заполнен';
    }
    if (!isset($data['user_pass']) || empty($data['user_pass'])) {
      $errors[] = 'Пароль не заполнен';
    }
    if (isset($data['user_pass_repeat']) && $data['user_pass'] !== $data['user_pass_repeat']) {
      $errors[] = 'Пароли не совпадают';
    }

    if (isset($data['role'])) {
      if (strtolower($data['role']) === 'administrator' || strtolower($data['role']) === 'editor') {
        $errors[] = 'Нельзя создать аккаунт администратора';
      }
    }

    if (count($errors)) {
      $response['error'] = new \WP_Error('insert_user_errors', $errors, ['status' => 422]);
      $response['message'] = implode(' ', $errors);
      return $response;
    }

    if (!isset($data['role'])) {
      $data['role'] = 'subscriber';
    }

    $data['show_admin_bar_front'] = 'false';

    $user_id = wp_insert_user($data);

    if (!$user_id || is_wp_error($user_id)) {
      $response['message'] = implode(' ', $user_id->get_error_messages());
      $response['error'] = $user_id->get_error_messages();
      return $response;
    }

    foreach (self::META as $key => $value) {
      if (isset($data[$value])) {
        update_user_meta($user_id, $value, $data[$value]);
      }
    }

    $data['ID'] = $user_id;

    $this->login($data);

    $response['status'] = 'success';
    $response['message'] = 'Регистрация прошла успешно!';

    return $response;
  }

  public function login($data)
  {
    $user = false;

    $response['status'] = 'fail';
    $response['message'] = 'Что-то пошло не так(';
    $response['error'];

    if (isset($data['user_login'])) {
      $user = get_user_by('login', $data['user_login']);
    }

    if (!$user || is_wp_error($user)) {
      $user = get_user_by('email', $data['user_email']);
    }

    if (!$user) {
      $response['message'] = 'Не верный логин или пароль :(';
      $response['error'] = new \WP_Error('login_failed', 'Invalid username or password.');
      return $response;
    }

    $creds['user_login'] = $user->user_login;
    $creds['user_password'] = $data['user_pass'];

    if (isset($data['remember']) && $data['remember']) {
      $creds['remember'] = true;
    }

    $result = wp_signon($creds, false);

    if (!$result && is_wp_error($result)) {
      $response['message'] = 'Не верный логин или пароль :(';
      $response['error'] = $result->get_error_messages();
      return $response;
    }

    $response['status'] = 'success';
    $response['message'] = 'Вход выполнен!';

    return $response;
  }
}
