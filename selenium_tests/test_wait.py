from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
import time

driver = webdriver.Chrome()

driver.get("http://localhost/evently/login.php")

driver.find_element(By.ID, "loginEmail").send_keys("admin@evently.com")
driver.find_element(By.ID, "loginPassword").send_keys("123456")

driver.find_element(By.CSS_SELECTOR, "button.btn.full").click()

wait = WebDriverWait(driver, 10)

# Start timer
start_time = time.perf_counter()

# Conditional wait
wait.until(lambda d: "login.php" not in d.current_url)

# End timer
end_time = time.perf_counter()

elapsed = end_time - start_time

print("Conditional Wait")
print("Wait time:", round(elapsed, 2), "seconds")
print("Current URL:", driver.current_url)

input("SS নেওয়ার পরে Enter চাপুন...")

driver.quit()