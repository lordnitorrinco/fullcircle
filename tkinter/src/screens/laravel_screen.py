import tkinter as tk
from tkinter import ttk
import requests
import json
from bs4 import BeautifulSoup
from tkinterweb import HtmlFrame

class LaravelScreen(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller

        self.configure(bg="#2e2e2e")

        label = tk.Label(self, text="Laravel", font=("Arial", 24), bg="#2e2e2e", fg="#ffffff")
        label.pack(pady=10, padx=10)

        self.create_input_fields()
        self.create_response_area()

    def create_input_fields(self):
        input_frame = tk.Frame(self, bg="#2e2e2e")
        input_frame.pack(side=tk.LEFT, fill=tk.Y, pady=10, padx=10)

        # Section for Get All Tasks
        get_all_tasks_frame = tk.Frame(input_frame, bg="#2e2e2e")
        get_all_tasks_frame.pack(fill=tk.X, pady=5)
        get_all_tasks_button = tk.Button(get_all_tasks_frame, text="Get All Tasks", command=self.get_all_tasks, font=("Arial", 14), bg="#4CAF50", fg="#ffffff")
        get_all_tasks_button.pack(fill=tk.X)

        # Section for Get Completed Tasks
        get_completed_tasks_frame = tk.Frame(input_frame, bg="#2e2e2e")
        get_completed_tasks_frame.pack(fill=tk.X, pady=5)
        get_completed_tasks_button = tk.Button(get_completed_tasks_frame, text="Get Completed Tasks", command=self.get_completed_tasks, font=("Arial", 14), bg="#4CAF50", fg="#ffffff")
        get_completed_tasks_button.pack(fill=tk.X)

        # Section for Put Task
        put_task_frame = tk.Frame(input_frame, bg="#2e2e2e")
        put_task_frame.pack(fill=tk.X, pady=5)
        tk.Label(put_task_frame, text="Put Task", font=("Arial", 14), bg="#2e2e2e", fg="#ffffff").pack()
        tk.Label(put_task_frame, text="ID", font=("Arial", 12), bg="#2e2e2e", fg="#ffffff").pack()
        self.put_task_id_entry = tk.Entry(put_task_frame, width=40, font=("Arial", 14))
        self.put_task_id_entry.pack(pady=5)
        self.put_task_id_entry.bind("<KeyRelease>", self.update_put_task_button_state)
        tk.Label(put_task_frame, text="Title", font=("Arial", 12), bg="#2e2e2e", fg="#ffffff").pack()
        self.put_task_title_entry = tk.Entry(put_task_frame, width=40, font=("Arial", 14))
        self.put_task_title_entry.pack(pady=5)
        tk.Label(put_task_frame, text="Description", font=("Arial", 12), bg="#2e2e2e", fg="#ffffff").pack()
        self.put_task_description_entry = tk.Entry(put_task_frame, width=40, font=("Arial", 14))
        self.put_task_description_entry.pack(pady=5)
        tk.Label(put_task_frame, text="Status", font=("Arial", 12), bg="#2e2e2e", fg="#ffffff").pack()
        self.put_task_status_var = tk.StringVar()
        self.put_task_status_menu = ttk.Combobox(put_task_frame, textvariable=self.put_task_status_var, values=["pending", "in_progress", "completed"], font=("Arial", 14))
        self.put_task_status_menu.pack(pady=5)
        self.put_task_status_menu.bind("<<ComboboxSelected>>", self.update_put_task_button_state)
        self.put_task_button = tk.Button(put_task_frame, text="Put Task", command=self.put_task, font=("Arial", 14), bg="#4CAF50", fg="#ffffff", state=tk.DISABLED)
        self.put_task_button.pack(pady=5)

        # Section for Post Task
        post_task_frame = tk.Frame(input_frame, bg="#2e2e2e")
        post_task_frame.pack(fill=tk.X, pady=5)
        tk.Label(post_task_frame, text="Post Task", font=("Arial", 14), bg="#2e2e2e", fg="#ffffff").pack()
        tk.Label(post_task_frame, text="Title", font=("Arial", 12), bg="#2e2e2e", fg="#ffffff").pack()
        self.post_task_title_entry = tk.Entry(post_task_frame, width=40, font=("Arial", 14))
        self.post_task_title_entry.pack(pady=5)
        self.post_task_title_entry.bind("<KeyRelease>", self.update_post_task_button_state)
        tk.Label(post_task_frame, text="Description", font=("Arial", 12), bg="#2e2e2e", fg="#ffffff").pack()
        self.post_task_description_entry = tk.Entry(post_task_frame, width=40, font=("Arial", 14))
        self.post_task_description_entry.pack(pady=5)
        self.post_task_description_entry.bind("<KeyRelease>", self.update_post_task_button_state)
        tk.Label(post_task_frame, text="Status", font=("Arial", 12), bg="#2e2e2e", fg="#ffffff").pack()
        self.post_task_status_var = tk.StringVar()
        self.post_task_status_menu = ttk.Combobox(post_task_frame, textvariable=self.post_task_status_var, values=["pending", "in_progress", "completed"], font=("Arial", 14))
        self.post_task_status_menu.pack(pady=5)
        self.post_task_status_menu.bind("<<ComboboxSelected>>", self.update_post_task_button_state)
        self.post_task_button = tk.Button(post_task_frame, text="Post Task", command=self.post_task, font=("Arial", 14), bg="#4CAF50", fg="#ffffff", state=tk.DISABLED)
        self.post_task_button.pack(pady=5)

        # Section for Delete Task
        delete_task_frame = tk.Frame(input_frame, bg="#2e2e2e")
        delete_task_frame.pack(fill=tk.X, pady=5)
        tk.Label(delete_task_frame, text="Delete Task", font=("Arial", 14), bg="#2e2e2e", fg="#ffffff").pack()
        tk.Label(delete_task_frame, text="ID", font=("Arial", 12), bg="#2e2e2e", fg="#ffffff").pack()
        self.delete_task_id_entry = tk.Entry(delete_task_frame, width=40, font=("Arial", 14))
        self.delete_task_id_entry.pack(pady=5)
        self.delete_task_id_entry.bind("<KeyRelease>", self.update_delete_task_button_state)
        self.delete_task_button = tk.Button(delete_task_frame, text="Delete Task", command=self.delete_task, font=("Arial", 14), bg="#4CAF50", fg="#ffffff", state=tk.DISABLED)
        self.delete_task_button.pack(pady=5)

    def create_response_area(self):
        response_frame = tk.Frame(self, bg="#2e2e2e")
        response_frame.pack(side=tk.LEFT, fill=tk.BOTH, expand=True, pady=10, padx=10)

        self.response_text = tk.Text(response_frame, wrap=tk.WORD, width=80, height=20, bg="#1e1e1e", fg="#ffffff", insertbackground="#ffffff", font=("Arial", 14))
        self.response_text.pack(fill=tk.BOTH, expand=True)

        self.html_frame = HtmlFrame(response_frame)
        self.html_frame.pack(fill=tk.BOTH, expand=True)
        self.html_frame.load_website("")
        self.html_frame.pack_forget()  # Ocultar el frame HTML por defecto

    def get_all_tasks(self):
        self.send_request("GET", "http://localhost/api/tasks")

    def get_completed_tasks(self):
        self.send_request("GET", "http://localhost/api/tasks/completed")

    def put_task(self):
        task_id = self.put_task_id_entry.get()
        url = f"http://localhost/api/tasks/{task_id}"
        body = {}
        if self.put_task_title_entry.get():
            body["title"] = self.put_task_title_entry.get()
        if self.put_task_description_entry.get():
            body["description"] = self.put_task_description_entry.get()
        if self.put_task_status_var.get():
            body["status"] = self.put_task_status_var.get()
        self.send_request("PUT", url, body)

    def post_task(self):
        url = "http://localhost/api/tasks"
        body = {}
        if self.post_task_title_entry.get():
            body["title"] = self.post_task_title_entry.get()
        if self.post_task_description_entry.get():
            body["description"] = self.post_task_description_entry.get()
        if self.post_task_status_var.get():
            body["status"] = self.post_task_status_var.get()
        self.send_request("POST", url, body)

    def delete_task(self):
        task_id = self.delete_task_id_entry.get()
        url = f"http://localhost/api/tasks/{task_id}"
        self.send_request("DELETE", url)

    def send_request(self, method, url, body=None):
        try:
            if method == "GET":
                response = requests.get(url)
            elif method == "POST":
                response = requests.post(url, json=body, headers={"Content-Type": "application/json"})
            elif method == "PUT":
                response = requests.put(url, json=body, headers={"Content-Type": "application/json"})
            elif method == "DELETE":
                response = requests.delete(url)

            self.response_text.delete("1.0", tk.END)
            self.html_frame.pack_forget()
            self.display_response(response)
        except Exception as e:
            self.response_text.delete("1.0", tk.END)
            self.html_frame.pack_forget()
            self.response_text.insert(tk.END, str(e))

    def display_response(self, response):
        try:
            json_data = response.json()
            pretty_json = json.dumps(json_data, indent=4)
            self.response_text.pack(fill=tk.BOTH, expand=True)
            self.response_text.insert(tk.END, pretty_json)
        except ValueError:
            soup = BeautifulSoup(response.text, 'html.parser')
            pretty_html = soup.prettify()
            self.html_frame.pack(fill=tk.BOTH, expand=True)
            self.response_text.pack_forget()
            self.html_frame.load_html(pretty_html)

    def update_put_task_button_state(self, event=None):
        if self.put_task_id_entry.get():
            self.put_task_button.config(state=tk.NORMAL)
        else:
            self.put_task_button.config(state=tk.DISABLED)

    def update_post_task_button_state(self, event=None):
        if self.post_task_title_entry.get() and self.post_task_description_entry.get() and self.post_task_status_var.get():
            self.post_task_button.config(state=tk.NORMAL)
        else:
            self.post_task_button.config(state=tk.DISABLED)

    def update_delete_task_button_state(self, event=None):
        if self.delete_task_id_entry.get():
            self.delete_task_button.config(state=tk.NORMAL)
        else:
            self.delete_task_button.config(state=tk.DISABLED)
