# sdd-bus-tracker

A repository for a location-based bus tracking program, through which users can see ETAs of nearby buses. 
**Currently accessable via: [https://stlukes.link/bus](https://stlukes.link/bus).** 
<br>
<br>
<!--![St. Luke's Grammar School Bus Tracker Logo](imagesandresources/SLGSBTLogo.png)-->
<br>

____

Currently, this program only displays buses **to/from** St. Luke's Grammar (*Dee Why Campus _only_)*. Buses displayed are state-run buses, typically operated by Keolis Downer, or CDC Bus NSW.
____ 

<br>
Contact James, using the below details, to request a customised version for your educational institution, local bus stop, or personal use.
<br><br>



# Installation Instructions (Running Locally)
### Unix/MacOS/Linux etc.:

**Prerequisites:**

* Homebrew (Linux/MacOS/Unix) is installed)
	* If not, please visit the [homebrew website](https://brew.sh), or run this command to install it on your machine: `/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"`

**Instilation Guide:**

1. Clone this repository 
	2. Recommended to use a CLI for this step, as it will make the process of running locally a lot smoother. 
	3. `git clone https://github.com/nexuspcs/sdd-bus-tracker.git`
2. Ensure your working directory in your CLI is in the root folder of this newly-cloned repository
3. Ensure that the file `run-locally.sh` is visable.
4. Ensure that the file `run-locally.sh` is executable. This can be confrimed by running CMOD: `cmod +x run-locally.sh`. 
5. Run the `run-locally.sh` file: `./run-locally.sh`
6. This shell script will install PHP on your machine. ~ If installed already, it will check for updates, as this shell script is simply running this command: `brew install php`

Within this repository, contains the source code files for the SDD Task 2 Project, 2023. Please contact James using the following link: [James' email](mailto:jamesac2024@student.stlukes.nsw.edu.au).



~~For temporary access to this site, use the following link [https://nexuspcs.github.io/sdd-bus-tracker/](https://nexuspcs.github.io/sdd-bus-tracker/). _please note: this link is not able to run PHP code, therefore only a static page will be shown..._~~



### Mirrors (no-optimisation):

[https://slgs-bustracker.000webhostapp.com/main.php](https://slgs-bustracker.000webhostapp.com/main.php) <br>
[http://slgsbuses.000.pe/main.php](http://slgsbuses.000.pe/main.php) <br>
These mirrors are able to run the PHP code, but is only up to date with our progress WHEN we _upload_ files, _not_ when commits are uploaded via GitHub. FTP upload is in use for *000webhostapp*, and also *000.pe*, but is not live with GitHub.

