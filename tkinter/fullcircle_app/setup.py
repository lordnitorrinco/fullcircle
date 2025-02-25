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
