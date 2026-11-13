import pytest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

URL = "http://localhost/evently/login.php"


@pytest.fixture
def driver():
    d = webdriver.Chrome()
    d.maximize_window()
    yield d
    d.quit()


def login(driver, email, password):

    driver.get(URL)

    # Wait until login page is loaded
    email_box = WebDriverWait(driver, 10).until(
        EC.visibility_of_element_located((By.ID, "loginEmail"))
    )

    password_box = WebDriverWait(driver, 10).until(
        EC.visibility_of_element_located((By.ID, "loginPassword"))
    )

    login_button = WebDriverWait(driver, 10).until(
        EC.element_to_be_clickable(
            (By.CSS_SELECTOR, "button.btn.full")
        )
    )

    email_box.send_keys(email)
    password_box.send_keys(password)
    login_button.click()


def test_admin_can_log_in(driver):

    login(driver, "admin@evently.com", "123456")

    WebDriverWait(driver, 10).until(
        lambda d: "login.php" not in d.current_url
    )

    assert "login.php" not in driver.current_url

    print("Admin login successful")
    print("Current URL:", driver.current_url)


def test_wrong_password_is_rejected(driver):

    login(driver, "admin@evently.com", "wrongpass")

    error_message = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located(
            (
                By.XPATH,
                "//*[contains(text(), 'Invalid email or password.')]"
            )
        )
    )

    assert "invalid" in error_message.text.lower()

    print("Wrong password rejected successfully")
    print("Message:", error_message.text)