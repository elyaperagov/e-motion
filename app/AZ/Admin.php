<?

namespace AZ;

class Admin
{
  public static function showNotice()
  {
    if (!isset($_SESSION['admin_notice'])) {
      return;
    }

    echo $_SESSION['admin_notice'];

    unset($_SESSION['admin_notice']);
  }
}
