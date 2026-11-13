from selenium import webdriver
from selenium.webdriver.common.by import By
import time

driver = webdriver.Chrome()

# Login page
driver.get("http://localhost/evently/login.php")

# Login
driver.find_element(By.ID, "loginEmail").send_keys("admin@evently.com")
driver.find_element(By.ID, "loginPassword").send_keys("123456")

driver.find_element(By.CSS_SELECTOR, "button.btn.full").click()

time.sleep(3)

print("After login:", driver.current_url)

# Logout
driver.get("http://localhost/evently/logout.php")

time.sleep(2)

print("After logout:", driver.current_url)

# Screenshot
driver.save_screenshot("logout_test.png")

input("Screenshot নেওয়ার পরে Enter চাপুন...")

driver.quit()

print("Logout test finished.")