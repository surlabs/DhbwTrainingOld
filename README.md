ILIAS-Plugin DhbwTraining
============
This is a fork created by SURLABS of the ingoj DhbwTraining Plugin for ILIAS

This version is valid for ILIAS6 and ILIAS7 with PHP 7.0 or 7.4


## Installation
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/Repository/RepositoryObject
cd Customizing/global/plugins/Services/Repository/RepositoryObject
git clone https://github.com/surlabs/DhbwTraining.git DhbwTraining
```
As ILIAS administrator go to "Administration->Plugins" and install/activate the plugin.  

## Usage
### Competences

"competences": {
    "4": 6,
    "7": 5
}
![](docs/competence_skill_id.png)

![](docs/Portfolio.png)
    
### Progress Meters

"progress_meters": [
{
  "progressmeter_type": "0",
  "title": "Ihr Fortschritt",
  "max_width_in_pixel": "",
  "max_reachable_score": "100",
  "required_score": "80",
  "primary_reached_score": "40",
  "secondary_reached_score": "60"
}
]

![](docs/Progressmeter.png)

### Requirements
* ILIAS 6.0 or ILIAS 7
* PHP =7.0 or 7.4
* Recommender Phython Software

### Maintenance
This project is maintained by Surlabs S.L. info@surlabs.es
