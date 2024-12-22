REAL TIME FACE DETECTION & RECOGNITION USING PYTHON & MYSQL DATABASE

Users can detect their face and system will show their Biodata (their picture and informations should be available in the dataset).
Admin can view User's history in the admin_dashboard.php page.
Feel Free to use this project ~

================================================================

1. install all dependencies (pip install -r requirements.txt on ur terminal).
2. setup ur dataset in the '/data' folder, make sure to rename the folders in '/data' to ur needs .
3. go to phpmyadmin and create a database named "face_detection", don't forget to create 'detection_history' and 'users' table (or just import the .sql file)
4. run train_dataset.py with "python train_dataset.py" on ur project directory terminal (this will train ur dataset into a model in the /model folder).
5. test ur model by running "python test_model.py" on ur project directory terminal (press q to stop the camera).
6. check ur database to see if the model sends the output to the database.
7. simply run the website through localhost.