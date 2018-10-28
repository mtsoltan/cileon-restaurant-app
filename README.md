# Cileon Restaurant App
A lightweight web application to administrate restaurants, written in PHP on top of CodeIgniter framework.
The application works in English and is being translated to Dutch.

To run this code:
1. Clone this repository.
2. Grab a copy of CodeIgniter 3 from the CodeIgniter website and place it in `/codeigniter`.
3. Remove the folder `/codeigniter/application` and change the variable `$application_folder` in `/codeigniter/index.php` to `__DIR__ . '/../' . 'application'`.
4. Place actual files in the place of each `.example` file present in the repository.
   You can use the `.example` files as a template.
5. Look at `/folder_structure.txt` and grab all of the dependencies listed in it from their respective sources, then place them in their places listed in `/folder_structure.txt`.
6. Run the repository in the PHP web server of your choice, with the site root at `/public`.

All the code in this repository, with exception of any git-ignored files, is built by the owner of this Github account and the sole person who commited to this repository. Please read COPYING.md before attempting to copy this software.