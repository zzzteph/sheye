package com.scanner.screenshot;
import java.io.IOException;
import org.apache.commons.io.FileUtils;
import java.util.concurrent.TimeUnit;
import java.io.File;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeOptions;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.OutputType;
import org.openqa.selenium.TakesScreenshot;
import org.openqa.selenium.By;
import java.util.Iterator;
import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.FileReader;
import java.io.IOException;
import java.util.Map;
import java.io.FileReader;
import java.util.Iterator;
import java.util.Random;
import java.io.FileWriter;
import java.io.BufferedWriter;
import org.apache.commons.cli.*;
public class App {

    public static void main(String[] args) {

        String url = "";
        String screenshot = "";
		String source = "";
        Options options = new Options();
        Option HostInput = new Option("u", "url", true, "url");
        HostInput.setRequired(false);
        options.addOption(HostInput);
        Option ScreenShotInput = new Option("s", "screenshot", true, "screenshot");
        ScreenShotInput.setRequired(false);
        options.addOption(ScreenShotInput);

        Option SourceOption = new Option("c", "source", true, "source");
        SourceOption.setRequired(false);
        options.addOption(SourceOption);

        CommandLineParser parser = new DefaultParser();
        HelpFormatter formatter = new HelpFormatter();
        try {
            CommandLine cmd;

            cmd = parser.parse(options, args);
            url = cmd.getOptionValue("url");
            screenshot = cmd.getOptionValue("screenshot");
	    source = cmd.getOptionValue("source");
        } catch (Exception e) {
            System.out.println(e.getMessage());
            formatter.printHelp("utility-name", options);
            System.exit(1);
        }
        makeAction(url, screenshot,source);

        return;
    }


   public static void getSource(WebDriver driver,String source)
   {
try 
{
    BufferedWriter writer = new BufferedWriter(new FileWriter(source));
    writer.write(driver.getPageSource());
    
    writer.close();
}
catch(Exception e)
{
}

   } 

    public static void takeScreenshot(WebDriver driver, String screenshot) {

        File src = ((TakesScreenshot) driver).getScreenshotAs(OutputType.FILE);
        try {
            FileUtils.copyFile(src, new File(screenshot));
        } catch (IOException e) {

            e.printStackTrace();
        }
    }



    public static boolean makeAction(String url, String screenshot,String source) {
        System.setProperty("webdriver.chrome.driver", "/usr/bin/chromedriver");
        System.setProperty("webdriver.chrome.silentOutput", "true");
        java.util.logging.Logger.getLogger("org.openqa.selenium").setLevel(java.util.logging.Level.OFF);
        ChromeOptions chromeOptions = new ChromeOptions();
        chromeOptions.addArguments("--headless");
        chromeOptions.addArguments("--no-sandbox");
        chromeOptions.addArguments("--disable-popup-blocking");
        chromeOptions.addArguments("--ignore-certificate-errors");
        chromeOptions.addArguments("--window-size=1920,1080");
        chromeOptions.addArguments("--log-level=3");
        chromeOptions.addArguments("--silent");
        WebDriver driver = new ChromeDriver(chromeOptions);

        try {
            driver.manage().timeouts().pageLoadTimeout(10, TimeUnit.SECONDS);
            driver.manage().timeouts().setScriptTimeout(10, TimeUnit.SECONDS);
            driver.manage().timeouts().implicitlyWait(10, TimeUnit.SECONDS);

			
	      driver.get(url);
              Thread.sleep(5000);
              takeScreenshot(driver, screenshot);
	      getSource(driver, source);
               	

        } catch (Exception e) {
            System.out.println(e);
            driver.quit();
            return false;
        }
        driver.quit();
        return true;

    }

}
