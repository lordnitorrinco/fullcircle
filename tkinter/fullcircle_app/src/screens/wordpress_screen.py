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
