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

