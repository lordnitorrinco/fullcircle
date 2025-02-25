#!/bin/bash

# Crear la estructura de directorios
mkdir -p src/screens

# Crear requirements.txt
cat <<EOL > requirements.txt
docker
tkinterweb
beautifulsoup4
screeninfo
EOL

# Crear setup.py
cat <<EOL > setup.py
from setuptools import setup, find_packages

setup(
    name='fullcircle_app',
    version='1.0',
    packages=find_packages(),
    install_requires=[
        'docker',
    ],
    entry_points={
        'console_scripts': [
            'fullcircle_app = src.main:main',
        ],
    },
)
EOL

# Crear __init__.py en src
touch src/__init__.py

# Crear __init__.py en screens
touch src/screens/__init__.py

# Crear main.py
cat <<EOL > src/main.py
import tkinter as tk
from tkinter import ttk
from screens.home_screen import HomeScreen
from screens.php_screen import PhpScreen
from screens.laravel_screen import LaravelScreen
from screens.wordpress_screen import WordpressScreen
from screens.mysql_screen import MysqlScreen
from screeninfo import get_monitors

class FullCircleApp(tk.Tk):
    def __init__(self):
        super().__init__()
        self.title("Full Circle App")
        self.attributes('-fullscreen', True)  # Configurar pantalla completa
        self.center_window()

        self.configure(bg="#2e2e2e")

        style = ttk.Style(self)
        style.theme_use("clam")
        style.configure("TNotebook", background="#2e2e2e", foreground="#ffffff")
        style.configure("TNotebook.Tab", background="#3e3e3e", foreground="#ffffff", font=("Arial", 20))
        style.map("TNotebook.Tab", background=[("selected", "#1e1e1e")])

        self.create_layout()

    def center_window(self):
        self.update_idletasks()
        width = self.winfo_width()
        height = self.winfo_height()
        monitor = self.get_current_monitor()
        x = (monitor.width // 2) - (width // 2) + monitor.x
        y = (monitor.height // 2) - (height // 2) + monitor.y
        self.geometry(f'{width}x{height}+{x}+{y}')

    def get_current_monitor(self):
        # Obtener la posición del cursor
        cursor_x, cursor_y = self.winfo_pointerxy()
        # Encontrar el monitor en el que se encuentra el cursor
        for monitor in get_monitors():
            if monitor.x <= cursor_x <= monitor.x + monitor.width and monitor.y <= cursor_y <= monitor.y + monitor.height:
                return monitor
        return get_monitors()[0]  # Devolver el monitor principal si no se encuentra el cursor

    def create_layout(self):
        # Crear el frame para HomeScreen
        home_frame = HomeScreen(self, self)
        home_frame.place(relwidth=0.25, relheight=1.0)

        # Crear el frame para las pestañas
        tab_frame = tk.Frame(self, bg="#2e2e2e")
        tab_frame.place(relx=0.25, relwidth=0.75, relheight=1.0)

        self.tab_control = ttk.Notebook(tab_frame)
        self.frames = {}
        self.create_tabs()

    def create_tabs(self):
        self.check_containers()

    def check_containers(self):
        import docker
        client = docker.from_env()
        containers = {
            "php": ["php_container"],
            "laravel": ["laravel-app-1", "laravel-mysql-1"],
            "wordpress": ["wordpress-app-1", "wordpress-mysql-1"],
            "mysql": ["mysql_container"]
        }
        tabs = {
            "php": PhpScreen,
            "laravel": LaravelScreen,
            "wordpress": WordpressScreen,
            "mysql": MysqlScreen
        }
        for name, container_names in containers.items():
            if all(client.containers.get(container).status == "running" for container in container_names):
                if name not in self.frames:
                    frame = tabs[name](self.tab_control, self)
                    self.tab_control.add(frame, text=name.capitalize())
                    self.frames[name] = frame
            else:
                if name in self.frames:
                    frame = self.frames.pop(name)
                    self.tab_control.forget(frame)

        self.tab_control.pack(expand=1, fill="both")
        self.after(5000, self.check_containers)  # Actualizar cada 5 segundos

def main():
    app = FullCircleApp()
    app.mainloop()

if __name__ == "__main__":
    main()
EOL

# Crear __init__.py en screens
touch src/__init__.py

# Crear home_screen.py
cat <<EOL > src/screens/home_screen.py
import tkinter as tk
import docker

class HomeScreen(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller

        self.configure(bg="#2e2e2e")

        label = tk.Label(self, text="Docker containers", font=("Arial", 24), bg="#2e2e2e", fg="#ffffff")
        label.pack(pady=10, padx=10)

        self.docker_client = docker.from_env()
        self.container_frames = {}
        self.update_containers()

    def update_containers(self):
        container_names = [
            "mysql_container",
            "php_container",
            "wordpress-app-1",
            "wordpress-mysql-1",
            "laravel-app-1",
            "laravel-mysql-1"
        ]
        for name in container_names:
            try:
                container = self.docker_client.containers.get(name)
                status = container.status
            except docker.errors.NotFound:
                status = "not found"

            if name not in self.container_frames:
                frame = tk.Frame(self, bg="#2e2e2e")
                frame.pack(fill="x", pady=5)
                label = tk.Label(frame, text=name, font=("Arial", 20), bg="#2e2e2e", fg="#ffffff")
                label.pack(side="left", padx=10)
                status_indicator = tk.Canvas(frame, width=20, height=20, bg="#2e2e2e", highlightthickness=0)
                status_indicator.pack(side="right", padx=10)
                self.container_frames[name] = (frame, status_indicator)
            else:
                frame, status_indicator = self.container_frames[name]
                status_indicator.delete("all")

            if status == "running":
                status_indicator.create_oval(5, 5, 15, 15, fill="green")
            else:
                status_indicator.create_oval(5, 5, 15, 15, fill="red")

        self.after(5000, self.update_containers)  # Actualizar cada 5 segundos
EOL

# Crear php_screen.py
cat <<EOL > src/screens/php_screen.py
import tkinter as tk
import subprocess

class PhpScreen(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller

        self.configure(bg="#2e2e2e")

        label = tk.Label(self, text="PHP", font=("Arial", 24), bg="#2e2e2e", fg="#ffffff")
        label.pack(pady=10, padx=10)

        command_frame = tk.Frame(self, bg="#2e2e2e")
        command_frame.pack(pady=10, padx=10, side=tk.TOP, fill=tk.X)

        command_label = tk.Label(command_frame, text="fullcircle", font=("Arial", 20), bg="#2e2e2e", fg="#ffffff")
        command_label.pack(side=tk.LEFT, padx=10)

        self.command_var = tk.StringVar(self)
        self.command_var.set("--help")  # Comando por defecto

        commands = ["--help", "question1", "question2", "question3"]

        self.command_menu = tk.OptionMenu(command_frame, self.command_var, *commands, command=self.update_options)
        self.command_menu.config(bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        self.command_menu["menu"].config(bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        self.command_menu.pack(side=tk.LEFT, padx=10)

        self.option_var = tk.StringVar(self)
        self.option_var.set("")  # Opción por defecto vacía

        self.option_menu = tk.OptionMenu(command_frame, self.option_var, "", command=self.update_options)
        self.option_menu.config(bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        self.option_menu["menu"].config(bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        self.option_menu.pack(side=tk.LEFT, padx=10)

        self.run_button = tk.Button(command_frame, text="Run Command", command=self.run_command, bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        self.run_button.pack(side=tk.LEFT, padx=10)

        self.terminal = tk.Text(self, wrap=tk.WORD, width=80, height=20, bg="#1e1e1e", fg="#ffffff", insertbackground="#ffffff", font=("Courier", 20), padx=10, pady=10)
        self.terminal.pack(pady=10, padx=10)
        self.terminal.config(state=tk.DISABLED)  # Deshabilitar la entrada directa

        self.manual_input_frame = tk.Frame(self, bg="#2e2e2e")

        self.run_command()  # Ejecutar el comando por defecto al iniciar

    def update_options(self, *args):
        selected_command = self.command_var.get()
        menu = self.option_menu["menu"]
        menu.delete(0, "end")

        if selected_command in ["question1", "question2", "question3"]:
            self.option_var.set("--random")  # Opción por defecto
            menu.add_command(label="--random", command=lambda: self.option_var.set("--random"))
            menu.add_command(label="--manual", command=lambda: self.option_var.set("--manual"))
        else:
            self.option_var.set("")  # Opción vacía

        # Mostrar la terminal y ocultar el formulario manual
        self.terminal.pack(pady=10, padx=10)
        self.manual_input_frame.pack_forget()

    def run_command(self):
        command = f"fullcircle {self.command_var.get()} {self.option_var.get()}"
        if self.option_var.get() == "--manual":
            self.show_manual_input()
        else:
            self.execute_command(command)

    def show_manual_input(self):
        self.terminal.pack_forget()  # Ocultar la terminal
        self.manual_input_frame.pack(fill=tk.BOTH, expand=True, pady=10, padx=10)
        self.clear_manual_input_frame()

        if self.command_var.get() == "question1":
            self.create_array_input()
        elif self.command_var.get() == "question2":
            self.create_orders_input()
        elif self.command_var.get() == "question3":
            self.create_transactions_input()

    def clear_manual_input_frame(self):
        for widget in self.manual_input_frame.winfo_children():
            widget.destroy()

    def create_array_input(self):
        self.array_entries = []

        header = tk.Label(self.manual_input_frame, text="Enter Numbers (at least 2)", font=("Arial", 20), bg="#2e2e2e", fg="#ffffff")
        header.pack(pady=5)

        def add_entry():
            entry = tk.Entry(self.manual_input_frame, font=("Arial", 20))
            entry.pack(pady=5)
            self.array_entries.append(entry)

        def submit_array():
            array = [entry.get() for entry in self.array_entries if entry.get().isdigit()]
            if len(array) >= 2:
                self.execute_manual_command(array, "done")
            else:
                self.terminal.config(state=tk.NORMAL)
                self.terminal.insert(tk.END, "Please enter at least 2 numbers.\n")
                self.terminal.config(state=tk.DISABLED)

        add_button = tk.Button(self.manual_input_frame, text="Add Number", command=add_entry, bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        add_button.pack(pady=10)

        submit_button = tk.Button(self.manual_input_frame, text="Submit", command=lambda: [submit_array(), self.show_terminal()], bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        submit_button.pack(pady=10)

    def create_orders_input(self):
        self.orders_entries = []

        header = tk.Label(self.manual_input_frame, text="Status | Amount", font=("Arial", 20), bg="#2e2e2e", fg="#ffffff")
        header.pack(pady=5)

        def add_order():
            frame = tk.Frame(self.manual_input_frame, bg="#2e2e2e")
            frame.pack(pady=5)

            status_label = tk.Label(frame, text="Status:", font=("Arial", 20), bg="#2e2e2e", fg="#ffffff")
            status_label.pack(side=tk.LEFT, padx=5)
            status_var = tk.StringVar(frame)
            status_var.set("completed")  # Valor por defecto
            status_menu = tk.OptionMenu(frame, status_var, "completed", "pending", "cancelled")
            status_menu.config(bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
            status_menu["menu"].config(bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
            status_menu.pack(side=tk.LEFT, padx=5)

            amount_label = tk.Label(frame, text="Amount:", font=("Arial", 20), bg="#2e2e2e", fg="#ffffff")
            amount_label.pack(side=tk.LEFT, padx=5)
            amount_entry = tk.Entry(frame, font=("Arial", 20))
            amount_entry.pack(side=tk.LEFT, padx=5)

            self.orders_entries.append((status_var, amount_entry))

        def submit_orders():
            orders = []
            for status_var, amount_entry in self.orders_entries:
                if amount_entry.get().isdigit():
                    orders.append((status_var.get(), amount_entry.get()))
            if len(orders) >= 1:
                self.execute_manual_command(orders, "no")
            else:
                self.terminal.config(state=tk.NORMAL)
                self.terminal.insert(tk.END, "Please enter at least 1 order.\n")
                self.terminal.config(state=tk.DISABLED)

        add_button = tk.Button(self.manual_input_frame, text="Add Order", command=add_order, bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        add_button.pack(pady=10)

        submit_button = tk.Button(self.manual_input_frame, text="Submit", command=lambda: [submit_orders(), self.show_terminal()], bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        submit_button.pack(pady=10)

    def create_transactions_input(self):
        self.transactions_entries = []

        header = tk.Label(self.manual_input_frame, text="Amount | Date (YYYY-MM-DD) | Category", font=("Arial", 20), bg="#2e2e2e", fg="#ffffff")
        header.pack(pady=5)

        def add_transaction():
            frame = tk.Frame(self.manual_input_frame, bg="#2e2e2e")
            frame.pack(pady=5)

            amount_label = tk.Label(frame, text="Amount:", font=("Arial", 20), bg="#2e2e2e", fg="#ffffff")
            amount_label.pack(side=tk.LEFT, padx=5)
            amount_entry = tk.Entry(frame, font=("Arial", 20))
            amount_entry.pack(side=tk.LEFT, padx=5)

            date_label = tk.Label(frame, text="Date (YYYY-MM-DD):", font=("Arial", 20), bg="#2e2e2e", fg="#ffffff")
            date_label.pack(side=tk.LEFT, padx=5)
            date_entry = tk.Entry(frame, font=("Arial", 20))
            date_entry.pack(side=tk.LEFT, padx=5)

            category_label = tk.Label(frame, text="Category:", font=("Arial", 20), bg="#2e2e2e", fg="#ffffff")
            category_label.pack(side=tk.LEFT, padx=5)
            category_entry = tk.Entry(frame, font=("Arial", 20))
            category_entry.pack(side=tk.LEFT, padx=5)

            self.transactions_entries.append((amount_entry, date_entry, category_entry))

        def submit_transactions():
            transactions = []
            for amount_entry, date_entry, category_entry in self.transactions_entries:
                if amount_entry.get().isdigit() and date_entry.get():
                    transactions.append((amount_entry.get(), date_entry.get(), category_entry.get()))
            if len(transactions) >= 1:
                self.execute_manual_command(transactions, "no")
            else:
                self.terminal.config(state=tk.NORMAL)
                self.terminal.insert(tk.END, "Please enter at least 1 transaction.\n")
                self.terminal.config(state=tk.DISABLED)

        add_button = tk.Button(self.manual_input_frame, text="Add Transaction", command=add_transaction, bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        add_button.pack(pady=10)

        submit_button = tk.Button(self.manual_input_frame, text="Submit", command=lambda: [submit_transactions(), self.show_terminal()], bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        submit_button.pack(pady=10)

    def show_terminal(self):
        self.manual_input_frame.pack_forget()
        self.terminal.pack(pady=10, padx=10)

    def execute_command(self, command):
        self.terminal.config(state=tk.NORMAL)
        self.terminal.delete(1.0, tk.END)  # Limpiar la consola
        try:
            result = subprocess.run(["docker", "exec", "php_container", "sh", "-c", command], capture_output=True, text=True)
            self.terminal.insert(tk.END, f"$ {command}\n")
            self.terminal.insert(tk.END, result.stdout)
            self.terminal.insert(tk.END, result.stderr)
            self.terminal.insert(tk.END, "\n")
        except Exception as e:
            self.terminal.insert(tk.END, str(e))
        self.terminal.config(state=tk.DISABLED)

    def execute_manual_command(self, data, end_signal):
        self.terminal.config(state=tk.NORMAL)
        self.terminal.delete(1.0, tk.END)  # Limpiar la consola
        try:
            # Crear un proceso interactivo para pasar los datos uno a uno
            process = subprocess.Popen(["docker", "exec", "-i", "php_container", "sh", "-c", f"fullcircle {self.command_var.get()} --manual"], stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)

            for i, item in enumerate(data):
                if isinstance(item, tuple):
                    for sub_item in item:
                        process.stdin.write(f"{sub_item}\n")
                    if i < len(data) - 1:
                        process.stdin.write("yes\n")
                else:
                    process.stdin.write(f"{item}\n")
            process.stdin.write(f"{end_signal}\n")
            process.stdin.flush()

            stdout, stderr = process.communicate()
            # Filtrar las líneas de entrada de datos
            filtered_stdout = "\n".join(line for line in stdout.split("\n") if "Enter" not in line)
            self.terminal.insert(tk.END, filtered_stdout)
            self.terminal.insert(tk.END, stderr)
        except Exception as e:
            self.terminal.insert(tk.END, str(e))
        self.terminal.config(state=tk.DISABLED)

EOL

# Crear laravel_screen.py
cat <<EOL > src/screens/laravel_screen.py
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
EOL

# Crear wordpress_screen.py
cat <<EOL > src/screens/wordpress_screen.py
import tkinterweb
import tkinter as tk

class WordpressScreen(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller

        self.configure(bg="#2e2e2e")

        # Incluir un navegador web interactivo apuntando a localhost:8000
        frame = tkinterweb.HtmlFrame(self, messages_enabled=False)
        frame.load_website('http://localhost:8000')
        frame.pack(fill="both", expand=True)
EOL

# Crear mysql_screen.py
cat <<EOL > src/screens/mysql_screen.py
import tkinter as tk
import subprocess

class MysqlScreen(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller

        self.configure(bg="#2e2e2e")

        label = tk.Label(self, text="MySQL", font=("Arial", 24), bg="#2e2e2e", fg="#ffffff")
        label.pack(pady=10, padx=10)

        command_frame = tk.Frame(self, bg="#2e2e2e")
        command_frame.pack(pady=10, padx=10, side=tk.TOP, fill=tk.X)

        command_label = tk.Label(command_frame, text="Select Query", font=("Arial", 20), bg="#2e2e2e", fg="#ffffff")
        command_label.pack(side=tk.LEFT, padx=10)

        self.command_var = tk.StringVar(self)
        self.command_var.set("question1")  # Comando por defecto

        commands = ["question1", "question2", "question3"]

        self.command_menu = tk.OptionMenu(command_frame, self.command_var, *commands, command=self.update_options)
        self.command_menu.config(bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        self.command_menu["menu"].config(bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        self.command_menu.pack(side=tk.LEFT, padx=10)

        self.run_button = tk.Button(command_frame, text="Run Command", command=self.run_command, bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        self.run_button.pack(side=tk.LEFT, padx=10)

        self.terminal = tk.Text(self, wrap=tk.WORD, width=80, height=20, bg="#1e1e1e", fg="#ffffff", insertbackground="#ffffff", font=("Courier", 20), padx=10, pady=10)
        self.terminal.pack(pady=10, padx=10)
        self.terminal.config(state=tk.DISABLED)  # Deshabilitar la entrada directa

    def update_options(self, *args):
        # Actualizar opciones si es necesario
        pass

    def run_command(self):
        command = self.command_var.get()
        if command == "question1":
            sql_commands = [
                ["USE rooms_db;", "SHOW TABLES;"]
            ]
        elif command == "question2":
            sql_commands = [
                ["USE rooms_db;", "CALL GetAvailableRooms(CURDATE());"]
            ]
        elif command == "question3":
            sql_commands = [
                ["USE products_db;", "CALL GetTop5BestSellingProducts();"],
                ["USE products_db;", "CALL GetTotalRevenueLast30Days();"]
            ]
        else:
            sql_commands = []

        self.execute_sql_commands(sql_commands)

    def execute_sql_commands(self, sql_commands):
        self.terminal.config(state=tk.NORMAL)
        self.terminal.delete(1.0, tk.END)  # Limpiar la consola
        try:
            for command_group in sql_commands:
                process = subprocess.Popen(
                    ["docker", "exec", "-i", "mysql_container", "mysql", "-uroot", "-proot"],
                    stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True
                )
                for command in command_group:
                    process.stdin.write(f"{command}\n")
                process.stdin.flush()
                stdout, stderr = process.communicate()
                output = stdout + stderr
                # Filtrar la advertencia de seguridad
                output = "\n".join(line for line in output.splitlines() if "Using a password on the command line interface can be insecure." not in line)
                formatted_output = self.format_output(output)
                self.terminal.insert(tk.END, formatted_output + "\n\n")
        except Exception as e:
            self.terminal.insert(tk.END, str(e))
        self.terminal.config(state=tk.DISABLED)

    def format_output(self, output):
        lines = output.splitlines()
        formatted_output = []
        table_lines = []
        in_table = False

        for line in lines:
            if line.startswith("+") and not in_table:
                in_table = True
                table_lines.append(line)
            elif line.startswith("+") and in_table:
                table_lines.append(line)
                formatted_output.append("\n".join(table_lines))
                table_lines = []
                in_table = False
            elif in_table:
                table_lines.append(line)
            else:
                if table_lines:
                    formatted_output.append("\n".join(table_lines))
                    table_lines = []
                formatted_output.append(line)

        if table_lines:
            formatted_output.append("\n".join(table_lines))

        return "\n".join(formatted_output)

if __name__ == "__main__":
    root = tk.Tk()
    app = MysqlScreen(root, None)
    app.pack(fill="both", expand=True)
    root.mainloop()
EOL

